<?php

namespace Maba\Component\Monetary\Validation;

use Maba\Component\Math\MathInterface;
use Maba\Component\Monetary\Exception\InvalidAmountException;
use Maba\Component\Monetary\Exception\InvalidCurrencyException;
use Maba\Component\Monetary\Information\MoneyInformationProviderInterface;
use Maba\Component\Monetary\MoneyInterface;

class FinalMoneyValidator implements MoneyValidatorInterface
{
    protected $baseValidator;
    protected $informationProvider;
    protected $numberValidator;

    public function __construct(
        MoneyValidatorInterface $baseValidator,
        MathInterface $math,
        MoneyInformationProviderInterface $informationProvider
    ) {
        $this->baseValidator = $baseValidator;
        $this->math = $math;
        $this->informationProvider = $informationProvider;
    }

    /**
     * @param MoneyInterface $money
     *
     * @throws InvalidAmountException
     * @throws InvalidCurrencyException
     */
    public function validateMoney(MoneyInterface $money)
    {
        $this->baseValidator->validateMoney($money);

        $precision = $this->informationProvider->getDefaultPrecision($money->getCurrency());
        if (!$this->math->isEqual($money->getAmount(), $this->math->round($money->getAmount(), $precision))) {
            throw new InvalidAmountException(sprintf(
                'Too specific amount (%s) for currency (%s) specified',
                $money->getAmount(),
                $money->getCurrency()
            ));
        }
    }

} 