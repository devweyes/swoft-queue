<?php

namespace Jcsp\Queue\Contract;

interface QueueInterface
{
    /**
     * @param callable $callback
     * @param callable|null $fallback
     */
    public function receive(callable $callback, callable $fallback = null): void;

    /**
     * @param $message
     */
    public function push($message): void;

    /**
     * @return string
     */
    public function pop();

    /**
     * release
     */
    public function release(): void;

    /**
     * length
     * @return int
     */
    public function len(): int;

    /**
     * bing queue
     * @param string $queue
     * @param array $option
     * @return QueueInterface
     */
    public function bind(string $queue, array $option = []): QueueInterface;
}
