<?php


namespace Maba\Component\Monetary\Factory;


use Maba\Component\Monetary\Exception\InvalidAmountException;
use Maba\Component\Monetary\Exception\InvalidCurrencyException;
use Maba\Component\Monetary\MoneyInterface;

interface MoneyFactoryInterface
{

    /**
     * @param string $amount
     * @param string $currency
     *
     * @return MoneyInterface
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function create($amount, $currency);

    /**
     * Create zero Money
     *
     * @param string $currency
     *
     * @return MoneyInterface
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function createZero($currency = null);

    /**
     * @param int $amountInCents
     * @param string $currency
     *
     * @return MoneyInterface
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function createFromCents($amountInCents, $currency);
}
