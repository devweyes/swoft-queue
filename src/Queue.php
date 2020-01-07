<?php

namespace Jcsp\Queue;

use Swoft\Bean\BeanFactory;

class Queue
{
    public const MANAGER = 'queue.manager';
    public const DRIVER = 'queue.driver';
    public const SERIALIZER = 'queue.serializer';

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @throws RedisException
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $cacheManager = self::manager();
        return $cacheManager->{$method}(...$arguments);
    }

    /**
     * @return mixed|object|string
     */
    public static function manager()
    {
        return BeanFactory::getBean(self::MANAGER);
    }
}
