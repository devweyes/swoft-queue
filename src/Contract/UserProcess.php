<?php

namespace Jcsp\Queue\Contract;

abstract class UserProcess
{
    abstract public function receive($message): string;
}
