<?php

namespace Jcsp\Queue;

use Jcsp\Queue\Contract\QueueInterface;
use BadMethodCallException;

/**
 * Class QueueManager
 * @package Jcsp\Queue
 * @method string receive(callable $callback)
 */
class QueueManager
{
    /**
     * @var QueueInterface
     */
    private $driver;

    /**
     * get serverid.
     * @return string
     */
    public function getDriver(): QueueInterface
    {
        return $this->driver;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (!method_exists($this->driver, $name)) {
            throw new BadMethodCallException(sprintf('method:%s not found', $name));
        }
        return $this->driver->$name(...$arguments);
    }
}
