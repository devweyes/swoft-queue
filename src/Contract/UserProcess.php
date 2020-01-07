<?php

namespace Jcsp\Queue\Contract;

abstract class UserProcess extends \Swoft\Process\UserProcess
{
    /**
     * @var string|null
     */
    public $queue;
    /**
     * @var string|null
     */
    public $num;
    /**
     * @var string|null
     */
    public $rebot;
    /**
     * receivce message callback
     * @param $message
     * @return string
     */
    abstract public function receive($message): string;

    /**
     * when error callback
     * @param $message
     * @return string
     */
    public function fallback(\Throwable $throwable): void
    {
        //fallback
    }
}
