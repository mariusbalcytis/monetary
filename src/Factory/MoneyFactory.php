<?php

namespace Maba\Component\Monetary\Factory;

use Maba\Component\Math\MathInterface;
use Maba\Component\Monetary\Exception\InvalidAmountException;
use Maba\Component\Monetary\Exception\InvalidCurrencyException;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\Validation\MoneyValidator;

/**
 * Default money factory. Creates Money objects
 */
class MoneyFactory implements MoneyFactoryInterface
{
    protected $math;
    protected $validator;

    public function __construct(MathInterface $math, MoneyValidator $validator)
    {
        $this->math = $math;
        $this->validator = $validator;
    }

    /**
     * @param string $amount
     * @param string $currency
     *
     * @return Money
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function create($amount, $currency)
    {
        $result = new Money($amount, $currency);
        $this->validator->validateMoney($result);
        return $result;
    }

    /**
     * Create zero Money
     *
     * @param string $currency
     *
     * @return Money
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function createZero($currency = null)
    {
        return $this->create('0', $currency);
    }

    /**
     * @param int $amountInCents
     * @param string $currency
     *
     * @return Money
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function createFromCents($amountInCents, $currency)
    {
        return $this->create($this->math->div($amountInCents, '100'), $currency);
    }

} 