<?php

namespace Maba\Component\Monetary\Validation;

use Maba\Component\Monetary\Exception\InvalidAmountException;
use Maba\Component\Monetary\Exception\InvalidCurrencyException;
use Maba\Component\Monetary\MoneyInterface;

interface MoneyValidatorInterface
{
    /**
     * @param MoneyInterface $money
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function validateMoney(MoneyInterface $money);

} 