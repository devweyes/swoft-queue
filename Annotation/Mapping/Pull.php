<?php declare(strict_types=1);

namespace Jcsp\Queue\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class Customer
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("queue", type="string"),
 *     @Attribute("num", type="int"),
 *     @Attribute("rebot", type="bool")
 * })
 * @since 2.0
 */
final class Pull
{
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
        } elseif (isset($values['queue'])) {
            $this->queue = $values['queue'];
        }
        if (isset($values['num'])) {
            $this->num = $values['num'];
        }
        if (isset($values['rebot'])) {
            $this->rebot = $values['rebot'];
        }
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
