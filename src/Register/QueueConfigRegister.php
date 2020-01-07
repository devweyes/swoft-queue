<?php

namespace Jcsp\Queue\Register;

use Jcsp\Queue\Annotation\Mapping\Pull;

class QueueConfigRegister
{
    private static $redis = [];

    private static $rabbitmq = [];

    public static function register(Pull $pull, string $className, string $methodName): void
    {
        if (empty(self::$redis["$className@$methodName"])) {
            self::$redis["$className@$methodName"] = [
                'queue' => $pull->getQueue(),
                'num' => $pull->getNum(),
                'rebot' => $pull->getRebot()
            ];
        }
    }

    public static function get(string $className, string $methodName): array
    {
        return self::$redis["$className@$methodName"] ?? [];
    }
}
