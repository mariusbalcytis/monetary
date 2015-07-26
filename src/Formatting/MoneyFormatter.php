<?php

namespace Maba\Component\Monetary\Formatting;

use Maba\Component\Math\NumberFormatterInterface;
use Maba\Component\Monetary\Information\MoneyInformationProviderInterface;
use Maba\Component\Monetary\MoneyCalculatorInterface;
use Maba\Component\Monetary\MoneyInterface;

class MoneyFormatter
{
    protected $moneyCalculator;
    protected $informationProvider;
    protected $numberFormatter;
    protected $symbols;
    protected $defaultTemplate;

    public function __construct(
        MoneyCalculatorInterface $moneyCalculator,
        MoneyInformationProviderInterface $informationProvider,
        NumberFormatterInterface $numberFormatter,
        array $symbols = array(),
        $defaultTemplate = '%amount% %currency%'
    ) {
        $this->moneyCalculator = $moneyCalculator;
        $this->informationProvider = $informationProvider;
        $this->numberFormatter = $numberFormatter;
        $this->symbols = $symbols;
        $this->defaultTemplate = $defaultTemplate;
    }

    public function formatMoney(MoneyInterface $money = null, FormattingContext $context = null)
    {
        $context = $context !== null ? $context : new FormattingContext();

        if ($money === null) {
            return $context->getDefault();
        }

        $precision = $context->getPrecision();
        if ($precision === null) {
            $precision = $money->getCurrency() !== null
                ? $this->informationProvider->getDefaultPrecision($money->getCurrency())
                : 2;
        }

        $amount = $this->numberFormatter->formatNumber(
            $money->getAmount(),
            $precision,
            $context->getDecimalPoint(),
            $context->getThousandsSeparator()
        );

        if (!$context->isCurrencyIncludedOnZero() && $this->moneyCalculator->isZero($money)) {
            return $amount;

        } else {
            $symbol = isset($this->symbols[$money->getCurrency()])
                ? $this->symbols[$money->getCurrency()]
                : $money->getCurrency();

            $template = $context->getTemplate();
            if ($template === null) {
                $template = $this->defaultTemplate;
            }

            return strtr($template, array('%amount%' => $amount, '%currency%' => $symbol));
        }
    }
}
