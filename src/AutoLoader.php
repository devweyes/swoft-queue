<?php declare(strict_types=1);

namespace Jcsp\Queue;

use Jcsp\Queue\Driver\RedisQueue;
use Swoft\Console\Application;
use Swoft\Console\ConsoleDispatcher;
use Swoft\Console\Router\Router;
use Swoft\Helper\ComposerJSON;
use Swoft\Serialize\PhpSerializer;
use Swoft\SwoftComponent;
use function dirname;

/**
 * class AutoLoader
 *
 * @since 2.0
 */
final class AutoLoader extends SwoftComponent
{
    /**
     * @return bool
     */
    public function enable(): bool
    {
        return true;
    }

    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Metadata information for the component
     *
     * @return array
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }

    /**
     * {@inheritDoc}
     */
    public function beans(): array
    {
        return [
            Queue::MANAGER => [
                'class' => QueueManager::class,
                'driver' => bean(Queue::DRIVER),
                'serverIdPrefix' => 'swoft_ws_server_cluster_'
            ],
            Queue::DRIVER => [
                'class' => RedisQueue::class,
                'redis' => bean('redis.pool'),
                'serializer' => bean(Queue::SERIALIZER),
                'prefix' => 'swoft_queue_',
                'default' => 'default',
                'waite' => 10,
                'retry' => 3
            ],
            Queue::SERIALIZER => [
                'class' => PhpSerializer::class
            ]
        ];
    }
}
