<?php

namespace hiqdev\php\billing\charge\modifiers\event;

use hiqdev\php\billing\charge\ChargeInterface;
use League\Event\AbstractEvent;

class LeasingWasFinished extends AbstractEvent implements \JsonSerializable
{
    /**
     * @var ChargeInterface
     */
    private $charge;
    /**
     * @var \DateTimeImmutable
     */
    private $time;

    private function __construct(ChargeInterface $charge, \DateTimeImmutable $time)
    {
        $this->charge = $charge;
        $this->time = $time;
    }

    public static function onCharge(ChargeInterface $charge, \DateTimeImmutable $time): self
    {
        return new self($charge, $time);
    }

    /**
     * @return ChargeInterface
     */
    public function getCharge(): ChargeInterface
    {
        return $this->charge;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'price_id' => $this->charge->getPrice()->getId(),
            'part_id' => $this->charge->getPrice()->getTarget()->getId(),
        ];
    }
}
