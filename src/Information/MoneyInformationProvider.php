<?php

namespace Maba\Component\Monetary\Information;

/**
 * Default money information provider
 * Currency codes by ISO 4217
 * Default data taken from http://www.currency-iso.org/en/home/tables/table-a1.html
 * Only currently active currencies are provided in the list, if you need support for historical currencies, provide
 *  currency list to the constructor
 */
class MoneyInformationProvider implements MoneyInformationProviderInterface
{
    const DEFAULT_PRECISION = 2;

    static protected $defaultCurrencyPrecisions = array(
        'BHD' => 3,
        'BIF' => 0,
        'BYR' => 0,
        'CLF' => 4,
        'CLP' => 0,
        'DJF' => 0,
        'GNF' => 0,
        'IQD' => 3,
        'ISK' => 0,
        'JOD' => 3,
        'JPY' => 0,
        'KMF' => 0,
        'KRW' => 0,
        'KWD' => 3,
        'LYD' => 3,
        'OMR' => 3,
        'PYG' => 0,
        'RWF' => 0,
        'TND' => 3,
        'UGX' => 0,
        'UYI' => 0,
        'VND' => 0,
        'VUV' => 0,
        'XAF' => 0,
        'XOF' => 0,
        'XPF' => 0,
    );

    static protected $defaultAvailableCurrencies = array(
        'AED',
        'AFN',
        'ALL',
        'AMD',
        'ANG',
        'AOA',
        'ARS',
        'AUD',
        'AWG',
        'AZN',
        'BAM',
        'BBD',
        'BDT',
        'BGN',
        'BHD',
        'BIF',
        'BMD',
        'BND',
        'BOB',
        'BOV',
        'BRL',
        'BSD',
        'BTN',
        'BWP',
        'BYN',
        'BYR',
        'BZD',
        'CAD',
        'CDF',
        'CHE',
        'CHF',
        'CHW',
        'CLF',
        'CLP',
        'CNY',
        'COP',
        'COU',
        'CRC',
        'CUC',
        'CUP',
        'CVE',
        'CZK',
        'DJF',
        'DKK',
        'DOP',
        'DZD',
        'EGP',
        'ERN',
        'ETB',
        'EUR',
        'FJD',
        'FKP',
        'GBP',
        'GEL',
        'GHS',
        'GIP',
        'GMD',
        'GNF',
        'GTQ',
        'GYD',
        'HKD',
        'HNL',
        'HRK',
        'HTG',
        'HUF',
        'IDR',
        'ILS',
        'INR',
        'IQD',
        'IRR',
        'ISK',
        'JMD',
        'JOD',
        'JPY',
        'KES',
        'KGS',
        'KHR',
        'KMF',
        'KPW',
        'KRW',
        'KWD',
        'KYD',
        'KZT',
        'LAK',
        'LBP',
        'LKR',
        'LRD',
        'LSL',
        'LTL',
        'LYD',
        'MAD',
        'MDL',
        'MGA',
        'MKD',
        'MMK',
        'MNT',
        'MOP',
        'MRO',
        'MUR',
        'MVR',
        'MWK',
        'MXN',
        'MXV',
        'MYR',
        'MZN',
        'NAD',
        'NGN',
        'NIO',
        'NOK',
        'NPR',
        'NZD',
        'OMR',
        'PAB',
        'PEN',
        'PGK',
        'PHP',
        'PKR',
        'PLN',
        'PYG',
        'QAR',
        'RON',
        'RSD',
        'RUB',
        'RWF',
        'SAR',
        'SBD',
        'SCR',
        'SDG',
        'SEK',
        'SGD',
        'SHP',
        'SLL',
        'SOS',
        'SRD',
        'SSP',
        'STD',
        'SVC',
        'SYP',
        'SZL',
        'THB',
        'TJS',
        'TMT',
        'TND',
        'TOP',
        'TRY',
        'TTD',
        'TWD',
        'TZS',
        'UAH',
        'UGX',
        'USD',
        'USN',
        'UYI',
        'UYU',
        'UZS',
        'VEF',
        'VES',
        'VND',
        'VUV',
        'WST',
        'XAF',
        'XCD',
        'XOF',
        'XPF',
        'YER',
        'ZAR',
        'ZMW',
        'ZWL',
    );

    protected $currencyPrecisions;
    protected $availableCurrencies;

    public function __construct(array $currencyPrecisions = null, array $availableCurrencies = null)
    {
        $this->currencyPrecisions = $currencyPrecisions !== null
            ? $currencyPrecisions
            : self::$defaultCurrencyPrecisions;
        $this->availableCurrencies = $availableCurrencies !== null
            ? $availableCurrencies
            : self::$defaultAvailableCurrencies;
    }

    public function getDefaultPrecision($currency)
    {
        return isset($this->currencyPrecisions[$currency])
            ? $this->currencyPrecisions[$currency]
            : self::DEFAULT_PRECISION;
    }

    public function getSupportedCurrencies()
    {
        return $this->availableCurrencies;
    }

    public function addAvailableCurrencies(array $availableCurrencies, array $currencyPrecisions = null)
    {
        $this->currencyPrecisions = $currencyPrecisions !== null
            ? array_replace($this->currencyPrecisions, $currencyPrecisions)
            : $this->currencyPrecisions
        ;
        $this->availableCurrencies = array_unique(array_merge($this->availableCurrencies, $availableCurrencies));
    }
}
