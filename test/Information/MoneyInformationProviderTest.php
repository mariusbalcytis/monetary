<?php

namespace Maba\Component\Monetary\Tests\Information;

use PHPUnit_Framework_TestCase;
use Maba\Component\Monetary\Information\MoneyInformationProvider;

class MoneyInformationProviderTest extends PHPUnit_Framework_TestCase
{
    private $currencyPrecisions;
    private $availableCurrencies;

    public function setUp()
    {
        $this->currencyPrecisions = array(
            'BHD' => 3,
            'BIF' => 0,
            'BYR' => 0,
            'CLF' => 4,
            'CLP' => 0,
        ) ;
        $this->availableCurrencies = array(
            'BHD',
            'BIF',
            'BYR',
            'CLF',
            'CLP',
        );
    }

    /**
     * @param array $availableCurrencies
     * @param array $expected
     *
     * @dataProvider dataProviderGetSupportedCurrenciesAfterAddingNewOnes
     */
    public function testGetSupportedCurrenciesAfterAddingNewOnes(array $availableCurrencies, array $expected)
    {
        $moneyInformationProvider = new MoneyInformationProvider($this->currencyPrecisions,$this->availableCurrencies);
        $moneyInformationProvider->addAvailableCurrencies($availableCurrencies);

        $this->assertSame($expected, $moneyInformationProvider->getSupportedCurrencies());
    }

    public function dataProviderGetSupportedCurrenciesAfterAddingNewOnes()
    {
        return array(
            'adding existing currency' => array(
                array('BHD', 'BIF'),
                array('BHD', 'BIF', 'BYR', 'CLF', 'CLP'),
            ),
            'adding new currency' => array(
                array('XAU'),
                array('BHD', 'BIF', 'BYR', 'CLF', 'CLP', 'XAU'),
            ),
        );
    }

    /**
     * @param string $currency
     * @param array|null $precision
     * @param string $expected
     *
     * @dataProvider dataProviderGetDefaultCurrency
     */
    public function testGetDefaultPrecision($currency, $expected, array $precision = null)
    {
        $moneyInformationProvider = new MoneyInformationProvider($this->currencyPrecisions, $this->availableCurrencies);
        $moneyInformationProvider->addAvailableCurrencies(array($currency), $precision);

        $this->assertSame($expected, $moneyInformationProvider->getDefaultPrecision($currency));
    }

    public function dataProviderGetDefaultCurrency()
    {
        return array(
            'adding existing currency with different precision' => array(
                'BHD',
                4,
                array('BHD' => 4),
            ),
            'adding new currency with precision' => array(
                'XAU',
                6,
                array('XAU' => 6),
            ),
            'adding new currency without precision' => array(
                'XAU',
                MoneyInformationProvider::DEFAULT_PRECISION,
            ),
        );
    }
}
