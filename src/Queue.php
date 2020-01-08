<?php

namespace Jcsp\Queue;

use Jcsp\Queue\Contract\QueueInterface;
use Swoft\Bean\BeanFactory;

/**
 * Class Queue
 * @package Jcsp\Queue
 * @method static void receive(callable $callback, callable $fallback = null)
 * @method static void push($message)
 * @method static string pop()
 * @method static void release()
 * @method static int len()
 * @method static QueueInterface bind(string $queue, array $option = [])
 */
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
