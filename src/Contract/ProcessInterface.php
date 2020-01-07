<?php

namespace Jcsp\Queue\Contract;

use Swoft\Process\Contract\ProcessInterface as SwoftProcessInterface;

interface ProcessInterface extends SwoftProcessInterface
{
    public function receive($message): string;
}
