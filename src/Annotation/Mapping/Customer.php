<?php declare(strict_types=1);

namespace Jcsp\Queue\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class Customer
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("exchange", type="string"),
 *     @Attribute("routingKey", type="string"),
 *     @Attribute("queue", type="string"),
 *     @Attribute("num", type="int"),
 *     @Attribute("rebot", type="bool")
 * })
 * @since 2.0
 */
final class Customer
{
    /**
     * @Required
     * @var string
     */
    private $exchange = '';
    /**
     * @Required
     * @var string
     */
    private $routingKey = '';
    /**
     * @Required
     * @var string
     */
    private $queue = '';
    /**
     * @var int
     */
    private $num = 1;
    /**
     * @var bool
     */
    private $rebot = true;

    public function __construct()
    {
        if (isset($values['value'])) {
            $this->queue = $values['value'];
        } elseif (isset($values['entity'])) {
            $this->queue = $values['entity'];
        }
        if (isset($values['exchange'])) {
            $this->exchange = $values['exchange'];
        }
        if (isset($values['routingKey'])) {
            $this->routingKey = $values['routingKey'];
        }
        if (isset($values['num'])) {
            $this->num = $values['num'];
        }
        if (isset($values['exchange'])) {
            $this->exchange = $values['exchange'];
        }
        if (isset($values['rebot'])) {
            $this->rebot = $values['rebot'];
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

    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->queue;
    }

    /**
     * @return int
     */
    public function getNum(): int
    {
        return $this->num;
    }

    /**
     * @return bool|null
     */
    public function getRebot(): ?bool
    {
        return $this->rebot;
    }
}
