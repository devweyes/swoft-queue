<?php declare(strict_types=1);

namespace Jcsp\Queue\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Jcsp\Queue\Annotation\Mapping\Producter;

/**
 * Class ProducterParser
 *
 * @AnnotationParser(Producter::class)
 * @since 2.0
 * @package Jcsp\Queue\Annotation\Parser
 */
class ProducterParser extends Parser
{
    /**
     * @param int $type
     * @param CacheClear $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        d($annotationObject);
        return [];
    }
}
