<?php

namespace Jcsp\Queue\Contract;

interface QueueInterface
{
    public function receive(callable $callback, callable $fallback = null): void;

    public function push($message): void;

    public function pop(): string;

    public function release(): void;
    public function bind(string $queue, array $option = []): self;
}
