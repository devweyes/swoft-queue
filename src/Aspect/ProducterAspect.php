<?php

namespace Jcsp\WsCluster\Aspect;

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
use Jcsp\Queue\Annotation\Mapping\Producter;

/**
 * Class ProducterAspect
 *
 * @since 2.0
 *
 * @Aspect(order=1)
 *
 * @PointAnnotation(include={Producter::class})
 */
class ProducterAspect
{
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


        $result = $proceedingJoinPoint->proceed();
        // After around
        return $result;
    }
}
