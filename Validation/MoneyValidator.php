<?php

namespace Maba\Component\Monetary\Validation;

use Maba\Component\Math\Exception\InvalidNumberException;
use Maba\Component\Math\MathInterface;
use Maba\Component\Math\NumberValidatorInterface;
use Maba\Component\Monetary\Exception\InvalidAmountException;
use Maba\Component\Monetary\Exception\InvalidCurrencyException;
use Maba\Component\Monetary\Information\MoneyInformationProviderInterface;
use Maba\Component\Monetary\MoneyInterface;

class MoneyValidator implements MoneyValidatorInterface
{
    protected $math;
    protected $informationProvider;
    protected $numberValidator;

    public function __construct(
        MathInterface $math,
        MoneyInformationProviderInterface $informationProvider,
        NumberValidatorInterface $numberValidator
    ) {
        $this->math = $math;
        $this->informationProvider = $informationProvider;
        $this->numberValidator = $numberValidator;
    }

    /**
     * @param MoneyInterface $money
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function validateMoney(MoneyInterface $money)
    {
        try {
            $this->numberValidator->validateNumber($money->getAmount());
        } catch (InvalidNumberException $exception) {
            throw new InvalidAmountException(
                'Invalid amount for money specified: ' . $money->getAmount(),
                0,
                $exception
            );
        }

        if (!($this->math->isZero($money->getAmount()) && $money->getCurrency() === null)) {
            if (!is_string($money->getCurrency())) {
                throw new InvalidCurrencyException('Specified currency is not string');
            } elseif (!in_array($money->getCurrency(), $this->informationProvider->getSupportedCurrencies())) {
                throw new InvalidCurrencyException('Invalid currency specified: ' . $money->getCurrency());
            }
        }
    }

} 