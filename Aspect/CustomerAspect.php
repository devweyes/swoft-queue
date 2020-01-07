<?php

namespace Jcsp\WsCluster\Aspect;

use Jcsp\Queue\Contract\ProcessInterface;
use Jcsp\Queue\Contract\UserProcess;
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
use Jcsp\Queue\Annotation\Mapping\Customer;
use Swoft\Process\Process;
use Swoole\Coroutine;
use Swoole\Process\Pool;

/**
 * Class ClusterAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointAnnotation(include={Customer::class})
 */
class CustomerAspect
{
    private $sleep = 10;

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

        while (true) {
            if ($target instanceof ProcessInterface) {
                $this->processReceive($target, ...$args);
            }
            if ($target instanceof UserProcess) {
                $this->userReceive($target, ...$args);
            }
            Coroutine::sleep($this->sleep);
        }

        $result = $proceedingJoinPoint->proceed();
        // After around
        return $result;
    }

    public function processReceive(&$target, Pool $pool, int $workerId)
    {
        //TODO 消费数据
    }

    public function userReceive(&$target, Process $process)
    {
        //TODO 消费数据
    }

    public function receive(&$target, $message)
    {
        return $target->receive($message);
    }
}
