<?php

namespace Jcsp\Queue\Contract;

use Swoft\Process\Contract\ProcessInterface as SwoftProcessInterface;

interface ProcessInterface extends SwoftProcessInterface
{
    /**
     * receivce message callback
     * @param $message
     * @return string
     */
    public function receive($message): string;

    /**
     * when error callback
     * @param $message
     * @return string
     */
    public function fallback(\Throwable $throwable): void;
}
