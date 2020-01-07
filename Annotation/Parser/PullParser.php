<?php declare(strict_types=1);

namespace Jcsp\Queue\Annotation\Parser;

use Jcsp\Queue\Register\QueueConfigRegister;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Jcsp\Queue\Annotation\Mapping\Pull;

/**
 * Class PullParser
 *
 * @AnnotationParser(Pull::class)
 * @since 2.0
 * @package Jcsp\Queue\Annotation\Parser
 */
class PullParser extends Parser
{
    /**
     * @param int $type
     * @param Pull $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        QueueConfigRegister::register($annotationObject, $this->className, $this->methodName);
        return [];
    }
}
