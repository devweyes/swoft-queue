<?php

namespace Jcsp\Queue\Driver;

use Jcsp\Queue\Contract\QueueInterface;
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

    public function receive(callable $callback, callable $fallback = null): void
    {
        while (true) {
            $value = $this->pop();
            if ($value) {
                $count = 0;
                while ($count <= $this->retry) {
                    try {
                        $callback($value);
                        break;
                    } catch (\Throwable $exception) {
                        if ($count >= $this->retry) {
                            $this->push($value);
                        }
                        $count++;
                        if ($fallback) {
                            $fallback($exception, $count);
                        }
                    }
                }
            }
            if (!$value) {
                Coroutine($this->waite);
            }
        }
    }

    public function push($message): void
    {
        $value = $this->getSerializer()->serialize($message);
        $this->redis->lPush($this->getQueue(), $value);
    }

    public function pop(): string
    {
        $value = $this->redis->rPop($this->getQueue());
        return $this->getSerializer()->unserialize($value);
    }

    public function release(): void
    {
        // TODO: Implement release() method.
    }

    /**
     * @return string
     */
    protected function getQueue()
    {
        return $this->prefix . $this->defult;
    }

    public function bind(string $queue, array $option = []): self
    {
        $this->defult = $queue;
        return $this;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        if (!$this->serializer) {
            $this->serializer = new PhpSerializer();
        }

        return $this->serializer;
    }
}
