<?php

namespace Jcsp\Queue\Driver;

use Jcsp\Queue\Contract\QueueInterface;
use Jcsp\Queue\Result;
use Swoft\Redis\Pool;
use Swoft\Serialize\Contract\SerializerInterface;
use Swoft\Serialize\PhpSerializer;
use Swoole\Coroutine;

class RedisQueue implements QueueInterface
{
    /**
     * @var string
     */
    private $prefix = 'swoft_queue_';
    /**
     * @var string
     */
    private $defult = 'default';
    /**
     * @var Pool
     */
    private $redis;
    /**
     * Data serializer
     *
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var int
     */
    private $waite = 10;
    /**
     * @var int
     */
    private $retry = 3;

    /**
     * @param callable $callback
     * @param callable|null $fallback
     */
    public function receive(callable $callback, callable $fallback = null): void
    {
        Coroutine::sleep(random_int(1, $this->waite));
        while (true) {
            $message = $this->pop();
            if ($message) {
                $count = 0;
                while ($count <= $this->retry) {
                    try {
                        $result = $callback($message);
                        //确认消费或丢弃
                        if ($result === Result::ACK || $result === Result::DROP) {
                            break 1;
                        }
                    } catch (\Throwable $exception) {
                        if ($fallback) {
                            $fallback($exception, $message);
                        }
                    }
                    if ($count >= $this->retry) {
                        //$this->pushFall($message);
                    }
                    $count++;
                }
            }
            if (!$message) {
                Coroutine::sleep($this->waite);
            }
        }
    }

    /**
     * @param $message
     */
    public function push($message): void
    {
        $value = $this->getSerializer()->serialize($message);
        $this->redis->lPush($this->getQueue(), $value);
    }
    /**
     * @param $message
     */
    public function pushFall($message): void
    {
        $value = $this->getSerializer()->serialize($message);
        $this->redis->lPush($this->getQueue().':fall', $value);
    }
    /**
     * @return string
     */
    public function pop()
    {
        //$this->redis->eval(LuaScripts::pop(),[$this->getQueue(),$this->getQueue().':fall'],2);
        $value = $this->redis->rPop($this->getQueue());
        return $this->getSerializer()->unserialize($value);
    }

    /**
     *
     */
    public function release(): void
    {
        // TODO: Implement release() method.
    }

    /**
     * @return int
     */
    public function len(): int
    {
        return $this->redis->lLen($this->getQueue());
    }

    /**
     * @param string $queue
     * @param array $option
     * @return RedisQueue
     */
    public function bind(string $queue, array $option = []): QueueInterface
    {
        $this->defult = $queue;
        return $this;
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer(): SerializerInterface
    {
        if (!$this->serializer) {
            $this->serializer = new PhpSerializer();
        }

        return $this->serializer;
    }

    /**
     * @return string
     */
    protected function getQueue()
    {
        return $this->prefix . $this->defult;
    }
}
