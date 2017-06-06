<?php
/**
 * PHP Billing Library
 *
 * @link      https://github.com/hiqdev/php-billing
 * @package   php-billing
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\billing\price;

use hiqdev\php\billing\EntityInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\TypeInterface;
use hiqdev\php\units\QuantityInterface;

/**
 * Price.
 * @see PriceInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
abstract class AbstractPrice implements PriceInterface, EntityInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Target
     */
    protected $target;

    /**
     * @var Type
     */
    protected $type;

    public function __construct($id, TargetInterface $target, TypeInterface $type)
    {
        $this->id = $id;
        $this->target = $target;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     * Default sum calculation method: sum = price * usage.
     */
    public function calculateSum(QuantityInterface $quantity)
    {
        $usage = $this->calculateUsage($quantity);
        if ($usage === null) {
            return null;
        }

        $price = $this->calculatePrice($quantity);
        if ($price === null) {
            return null;
        }

        return $price->multiply($usage->getQuantity());
    }

    public static function create(array $data)
    {
        return new SinglePrice($data['id'], $data['target']);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'target' => $this->target,
        ];
    }

    /**
     * {@inheritdoc}
     */
    abstract public function calculateUsage(QuantityInterface $quantity);

    /**
     * {@inheritdoc}
     */
    abstract public function calculatePrice(QuantityInterface $action);
}