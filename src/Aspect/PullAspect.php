<?php

namespace Jcsp\Queue\Aspect;

use Jcsp\Queue\Contract\ProcessInterface;
use Jcsp\Queue\Contract\UserProcess;
use Jcsp\Queue\QueueManager;
use Jcsp\Queue\Register\QueueConfigRegister;
use Swoft\Aop\Annotation\Mapping\After;
use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\Annotation\Mapping\PointAnnotation;
use Swoft\Aop\Annotation\Mapping\PointBean;
use Swoft\Aop\Point\JoinPoint;
use Swoft\Aop\Point\ProceedingJoinPoint;
use Swoft\Bean\Annotation\Mapping\Inject;
use Jcsp\Queue\Annotation\Mapping\Pull;
use Swoft\Process\Process;
use Swoole\Coroutine;
use Swoole\Process\Pool;

/**
 * Class PullAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointAnnotation(include={Pull::class})
 */
class PullAspect
{
    private $sleep = 10;
    /**
     * @Inject()
     * @var QueueManager
     */
    private $queueManager;

    /**
     * @Around()
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     *
     * @return mixed
     */
    public function around(ProceedingJoinPoint $proceedingJoinPoint)
    {
        // Before around
        $className = $proceedingJoinPoint->getClassName();
        $methodName = $proceedingJoinPoint->getMethod();
        $args = $proceedingJoinPoint->getArgs();
        $target = $proceedingJoinPoint->getTarget();

        $result = $proceedingJoinPoint->proceed();
        // After around
        $config = QueueConfigRegister::get($className, $methodName);
        if (!empty($config)) {
            while (true) {
                if ($target instanceof ProcessInterface) {
                    $this->processReceive($target, $config['queue'], ...$args);
                }
                if ($target instanceof UserProcess) {
                    $this->userReceive($target, $target->queue ?: $config['queue'], ...$args);
                }
                Coroutine::sleep($this->sleep);
            }
        }
        return $result;
    }

    /**
     * 进程池消费
     * @param $target
     * @param Pool $pool
     * @param int $workerId
     */
    public function processReceive(&$target, string $queue, Pool $pool, int $workerId)
    {
        $this->queueManager->bind($queue)->receive(static function ($message) use ($target) {
            $target->receive($message);
        }, static function ($exception, $retry) {
            d($exception, $retry);
        });
    }

    /**
     * 用户进程消费
     * @param $target
     * @param Process $process
     */
    public function userReceive(&$target, string $queue, Process $process)
    {
        $this->queueManager->bind($queue)->receive(static function ($message) use ($target) {
            $target->receive($message);
        }, static function ($exception, $retry) {
            d($exception, $retry);
        });
    }
}
