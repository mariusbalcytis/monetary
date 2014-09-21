<?php


namespace Maba\Component\Monetary\Information;


interface MoneyInformationProviderInterface
{
    /**
     * @param string $currency
     * @return int
     */
    public function getDefaultPrecision($currency);

    /**
     * @return array
     */
    public function getSupportedCurrencies();

} 