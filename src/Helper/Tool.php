<?php declare(strict_types=1);

namespace Jcsp\WsCluster\Helper;

use Exception;
use Swoft\Bean\BeanFactory;

class Tool
{
    /**
     * @param string $name
     * @param $process
     * @param int $num
     * @return array
     */
    public static function moreProcess(string $name, $process, int $num = 1): array
    {
        $class = [];
        for ($i = 1; $i <= $num; $i++) {
            $class["$name:$i"] = $process;
        }

        return $class;
    }
}
