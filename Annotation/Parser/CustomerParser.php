<?php declare(strict_types=1);

namespace Jcsp\Queue\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Jcsp\Queue\Annotation\Mapping\Customer;

/**
 * Class ClusterParser
 *
 * @AnnotationParser(Customer::class)
 * @since 2.0
 * @package Jcsp\Queue\Annotation\Parser
 */
class CustomerParser extends Parser
{
    /**
     * @param int $type
     * @param CacheClear $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {

        return [];
    }
}
