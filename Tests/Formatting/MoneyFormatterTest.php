<?php


namespace Maba\Component\Monetary\Tests\Formatting;

use Maba\Component\Math\NumberFormatterInterface;
use Maba\Component\Monetary\Formatting\FormattingContext;
use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\Information\MoneyInformationProviderInterface;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyCalculatorInterface;
use Maba\Component\Monetary\MoneyInterface;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;
use \PHPUnit_Framework_MockObject_Builder_MethodNameMatch as MethodNameMatch;

class MoneyFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockObject|MethodNameMatch
     */
    protected $informationProvider;

    /**
     * @var MockObject|MethodNameMatch
     */
    protected $numberFormatter;

    /**
     * @var MockObject|MethodNameMatch
     */
    protected $moneyCalculator;

    /**
     * @var MoneyFormatter
     */
    protected $formatter;


    public function setUp()
    {
        /** @var MoneyCalculatorInterface|MockObject|MethodNameMatch $moneyCalculator */
        $this->moneyCalculator = $moneyCalculator = $this->getMock('Maba\Component\Monetary\MoneyCalculatorInterface');
        /** @var MoneyInformationProviderInterface|MockObject|MethodNameMatch $informationProvider */
        $this->informationProvider = $informationProvider = $this->getMock(
            'Maba\Component\Monetary\Information\MoneyInformationProviderInterface'
        );
        /** @var NumberFormatterInterface|MockObject|MethodNameMatch $numberFormatter */
        $this->numberFormatter = $numberFormatter = $this->getMock('Maba\Component\Math\NumberFormatterInterface');

        $this->formatter = new MoneyFormatter(
            $moneyCalculator,
            $informationProvider,
            $numberFormatter,
            array('USD' => '$'),
            'A %currency% B %amount% C'
        );

        $informationProvider
            ->method($this->equalTo('getSupportedCurrencies'))
            ->will($this->returnValue(array('ABC', 'USD')))
        ;
        $informationProvider
            ->method($this->equalTo('getDefaultPrecision'))
            ->will($this->returnValueMap(array(array('ABC', 3), array('USD', 2))))
        ;
        $moneyCalculator
            ->method($this->equalTo('isZero'))
            ->will($this->returnCallback(function(MoneyInterface $money) {
                return $money->getAmount() === '0';
            }))
        ;
    }

    public function testFormatMoney()
    {
        $amount = '1';
        $this->numberFormatter->expects($this->once())
            ->method($this->equalTo('formatNumber'))
            ->with($amount, 3, '.', '')
            ->will($this->returnValue('%QWE%'))
        ;
        $this->assertSame(
            'A ABC B %QWE% C',
            $this->formatter->formatMoney(new Money($amount, 'ABC'))
        );
    }

    public function testFormatMoneyWithSymbol()
    {
        $amount = '1';
        $this->numberFormatter->expects($this->once())
            ->method($this->equalTo('formatNumber'))
            ->with($amount, 2, '.', '')
            ->will($this->returnValue('%QWE%'))
        ;
        $this->assertSame(
            'A $ B %QWE% C',
            $this->formatter->formatMoney(new Money($amount, 'USD'))
        );
    }

    public function testFormatMoneyWithDefault()
    {
        $context = new FormattingContext();
        $context->setDefault('default');
        $this->assertSame(
            'default',
            $this->formatter->formatMoney(null, $context)
        );
    }

    public function testFormatMoneyWithZero()
    {
        $amount = '0';
        $this->numberFormatter->expects($this->once())
            ->method($this->equalTo('formatNumber'))
            ->with($amount, 3, '.', '')
            ->will($this->returnValue('%QWE%'))
        ;
        $this->assertSame(
            'A ABC B %QWE% C',
            $this->formatter->formatMoney(new Money($amount, 'ABC'))
        );
    }

    public function testFormatMoneyWithZeroAndNoCurrency()
    {
        $amount = '0';
        $this->numberFormatter->expects($this->once())
            ->method($this->equalTo('formatNumber'))
            ->with($amount, 3, '.', '')
            ->will($this->returnValue('%QWE%'))
        ;
        $context = new FormattingContext();
        $context->setCurrencyIncludedOnZero(false);
        $this->assertSame(
            '%QWE%',
            $this->formatter->formatMoney(new Money($amount, 'ABC'), $context)
        );
    }

    public function testFormatMoneyWithCustomTemplate()
    {
        $amount = '1';
        $this->numberFormatter->expects($this->once())
            ->method($this->equalTo('formatNumber'))
            ->with($amount, 3, '.', '')
            ->will($this->returnValue('%QWE%'))
        ;
        $context = new FormattingContext();
        $context->setTemplate('%amount%%currency%');
        $this->assertSame(
            '%QWE%ABC',
            $this->formatter->formatMoney(new Money($amount, 'ABC'), $context)
        );
    }

    public function testFormatMoneyWithFormattingParameters()
    {
        $amount = '1';
        $decimalPoint = 'decimal';
        $thousandsSeparator = 'thousands';
        $precision = 'precision';
        $this->numberFormatter->expects($this->once())
            ->method($this->equalTo('formatNumber'))
            ->with($amount, $precision, $decimalPoint, $thousandsSeparator)
            ->will($this->returnValue('%QWE%'))
        ;
        $context = new FormattingContext();
        $context->setPrecision($precision)->setDecimalPoint($decimalPoint)->setThousandsSeparator($thousandsSeparator);
        $this->assertSame(
            'A ABC B %QWE% C',
            $this->formatter->formatMoney(new Money($amount, 'ABC'), $context)
        );
    }
}
