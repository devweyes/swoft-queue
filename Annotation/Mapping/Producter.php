<?php declare(strict_types=1);

namespace Jcsp\Queue\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class Producter
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("exchange", type="string"),
 *     @Attribute("routingKey", type="string"),
 * })
 * @since 2.0
 */
final class Producter
{
    /**
     * @Required
     * @var string
     */
    private $exchange = '';
    /**
     * @var string
     */
    private $routingKey = '';

    public function __construct()
    {
        if (isset($values['value'])) {
            $this->queue = $values['value'];
        } elseif (isset($values['exchange'])) {
            $this->exchange = $values['exchange'];
        }

        if (isset($values['routingKey'])) {
            $this->routingKey = $values['routingKey'];
        }
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }
}
