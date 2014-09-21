<?php


namespace Maba\Component\Monetary\Tests;


use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyCalculator;

class MoneyCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\PHPUnit_Framework_MockObject_Builder_MethodNameMatch
     */
    protected $math;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\PHPUnit_Framework_MockObject_Builder_MethodNameMatch
     */
    protected $factory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\PHPUnit_Framework_MockObject_Builder_MethodNameMatch
     */
    protected $informationProvider;

    /**
     * @var MoneyCalculator
     */
    protected $calculator;

    protected $factoryReturn;

    public function setUp()
    {
        /** @var \Maba\Component\Math\MathInterface $math */
        $this->math = $math = $this->getMock('Maba\Component\Math\MathInterface');
        /** @var \Maba\Component\Monetary\Factory\MoneyFactoryInterface $factory */
        $this->factory = $factory = $this->getMock('Maba\Component\Monetary\Factory\MoneyFactoryInterface');
        /** @var \Maba\Component\Monetary\Information\MoneyInformationProviderInterface $informationProvider */
        $this->informationProvider = $informationProvider = $this->getMock(
            'Maba\Component\Monetary\Information\MoneyInformationProviderInterface'
        );

        $this->calculator = new MoneyCalculator($math, $factory, $informationProvider);

        $this->factoryReturn = 'factoryResult';
    }


    public function testAdd()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('add')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->add(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testAddWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('add')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertCallsFactory($resultAmount, 'USD');
        $this->assertReturnsFactoryResult(
            $this->calculator->add(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testAddWithDifferentCurrencies()
    {
        $this->calculator->add(new Money('1', 'EUR'), new Money('2', 'USD'));
    }

    public function testSub()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('sub')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->sub(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testSubWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('sub')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertCallsFactory($resultAmount, 'USD');
        $this->assertReturnsFactoryResult(
            $this->calculator->sub(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testSubWithDifferentCurrencies()
    {
        $this->calculator->sub(new Money('1', 'EUR'), new Money('2', 'USD'));
    }

    public function testDivMoney()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('div')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertSame(
            $resultAmount,
            $this->calculator->divMoney(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testDivMoneyWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('div')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertSame(
            $resultAmount,
            $this->calculator->divMoney(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    public function testDivMoneyWithPrecision()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $precision = 'precision';
        $finalResult = 'roundedResult';
        $this->math->expects($this->once())
            ->method('div')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->math->expects($this->once())
            ->method('round')
            ->with($resultAmount, $precision)
            ->willReturn($finalResult)
        ;
        $this->assertSame(
            $finalResult,
            $this->calculator->divMoney(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'), $precision)
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testDivMoneyWithDifferentCurrencies()
    {
        $this->calculator->divMoney(new Money('1', 'EUR'), new Money('2', 'USD'));
    }


    public function testDiv()
    {
        $firstAmount = 'first';
        $second = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('div')
            ->with($firstAmount, $second)
            ->willReturn($resultAmount)
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->div(new Money($firstAmount, 'EUR'), $second)
        );
    }

    public function testMul()
    {
        $firstAmount = 'first';
        $second = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('mul')
            ->with($firstAmount, $second)
            ->willReturn($resultAmount)
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->mul(new Money($firstAmount, 'EUR'), $second)
        );
    }

    public function testRound()
    {
        $amount = 'first';
        $precision = 'precision';
        $roundingMode = 'roundingMode';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('round')
            ->with($amount, $precision, $roundingMode)
            ->willReturn($resultAmount)
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->round(new Money($amount, 'EUR'), $precision, $roundingMode)
        );
    }

    public function testRoundWithDefaultPrecision()
    {
        $amount = 'first';
        $precision = 'precision';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('round')
            ->with($amount, $precision)
            ->willReturn($resultAmount)
        ;
        $this->informationProvider->expects($this->once())
            ->method($this->equalTo('getDefaultPrecision'))
            ->with('EUR')
            ->will($this->returnValue($precision))
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->round(new Money($amount, 'EUR'))
        );
    }

    public function testCeil()
    {
        $amount = 'first';
        $precision = 'precision';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('ceil')
            ->with($amount, $precision)
            ->willReturn($resultAmount)
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->ceil(new Money($amount, 'EUR'), $precision)
        );
    }

    public function testCeilWithDefaultPrecision()
    {
        $amount = 'first';
        $precision = 'precision';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('ceil')
            ->with($amount, $precision)
            ->willReturn($resultAmount)
        ;
        $this->informationProvider->expects($this->once())
            ->method($this->equalTo('getDefaultPrecision'))
            ->with('EUR')
            ->will($this->returnValue($precision))
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->ceil(new Money($amount, 'EUR'))
        );
    }

    public function testFloor()
    {
        $amount = 'first';
        $precision = 'precision';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('floor')
            ->with($amount, $precision)
            ->willReturn($resultAmount)
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->floor(new Money($amount, 'EUR'), $precision)
        );
    }

    public function testFloorWithDefaultPrecision()
    {
        $amount = 'first';
        $precision = 'precision';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('floor')
            ->with($amount, $precision)
            ->willReturn($resultAmount)
        ;
        $this->informationProvider->expects($this->once())
            ->method($this->equalTo('getDefaultPrecision'))
            ->with('EUR')
            ->will($this->returnValue($precision))
        ;
        $this->assertCallsFactory($resultAmount, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->floor(new Money($amount, 'EUR'))
        );
    }

    public function testGetCents()
    {
        $amount = 'first';
        $roundingMode = 'roundingMode';
        $resultAmount = 'result';
        $roundedResult = 'roundedResult';
        $this->math->expects($this->once())
            ->method('mul')
            ->with($amount, '100')
            ->willReturn($resultAmount)
        ;
        $this->math->expects($this->once())
            ->method('round')
            ->with($resultAmount, 0, $roundingMode)
            ->willReturn($roundedResult)
        ;
        $this->assertSame(
            $roundedResult,
            $this->calculator->getCents(new Money($amount, 'EUR'), $roundingMode)
        );
    }

    public function testIsNegative()
    {
        $amount = 'first';
        $result = 'result';
        $this->math->expects($this->once())
            ->method('isNegative')
            ->with($amount)
            ->willReturn($result)
        ;
        $this->assertSame(
            $result,
            $this->calculator->isNegative(new Money($amount, 'EUR'))
        );
    }

    public function testIsPositive()
    {
        $amount = 'first';
        $result = 'result';
        $this->math->expects($this->once())
            ->method('isPositive')
            ->with($amount)
            ->willReturn($result)
        ;
        $this->assertSame(
            $result,
            $this->calculator->isPositive(new Money($amount, 'EUR'))
        );
    }

    public function testIsZero()
    {
        $amount = 'first';
        $result = 'result';
        $this->math->expects($this->once())
            ->method('isZero')
            ->with($amount)
            ->willReturn($result)
        ;
        $this->assertSame(
            $result,
            $this->calculator->isZero(new Money($amount, 'EUR'))
        );
    }

    public function testNegate()
    {
        $amount = 'first';
        $result = 'result';
        $this->math
            ->method($this->equalTo('isZero'))
            ->will($this->returnValue(false))
        ;
        $this->math
            ->method($this->equalTo('mul'))
            ->will($this->returnValue($result))
        ;
        $this->assertCallsFactory($result, 'EUR');
        $this->assertReturnsFactoryResult(
            $this->calculator->negate(new Money($amount, 'EUR'))
        );
    }

    public function testIsEqual()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isEqual')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertSame(
            $resultAmount,
            $this->calculator->isEqual(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testIsEqualWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isEqual')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertSame(
            $resultAmount,
            $this->calculator->isEqual(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testIsEqualWithDifferentCurrencies()
    {
        $this->calculator->isEqual(new Money('1', 'EUR'), new Money('2', 'USD'));
    }

    public function testIsGt()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isGt')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertSame(
            $resultAmount,
            $this->calculator->isGt(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testIsGtWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isGt')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertSame(
            $resultAmount,
            $this->calculator->isGt(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testIsGtWithDifferentCurrencies()
    {
        $this->calculator->isGt(new Money('1', 'EUR'), new Money('2', 'USD'));
    }

    public function testIsGte()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isGte')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertSame(
            $resultAmount,
            $this->calculator->isGte(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testIsGteWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isGte')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertSame(
            $resultAmount,
            $this->calculator->isGte(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testIsGteWithDifferentCurrencies()
    {
        $this->calculator->isGte(new Money('1', 'EUR'), new Money('2', 'USD'));
    }

    public function testIsLt()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isLt')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertSame(
            $resultAmount,
            $this->calculator->isLt(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testIsLtWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isLt')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertSame(
            $resultAmount,
            $this->calculator->isLt(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testIsLtWithDifferentCurrencies()
    {
        $this->calculator->isLt(new Money('1', 'EUR'), new Money('2', 'USD'));
    }

    public function testIsLte()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isLte')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertSame(
            $resultAmount,
            $this->calculator->isLte(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testIsLteWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('isLte')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertSame(
            $resultAmount,
            $this->calculator->isLte(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testIsLteWithDifferentCurrencies()
    {
        $this->calculator->isLte(new Money('1', 'EUR'), new Money('2', 'USD'));
    }

    public function testComp()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('comp')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->assertSame(
            $resultAmount,
            $this->calculator->comp(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'EUR'))
        );
    }

    public function testCompWithZero()
    {
        $firstAmount = 'first';
        $secondAmount = 'second';
        $resultAmount = 'result';
        $this->math->expects($this->once())
            ->method('comp')
            ->with($firstAmount, $secondAmount)
            ->willReturn($resultAmount)
        ;
        $this->stubIsZeroForFirst($firstAmount, $secondAmount);
        $this->assertSame(
            $resultAmount,
            $this->calculator->comp(new Money($firstAmount, 'EUR'), new Money($secondAmount, 'USD'))
        );
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\CurrencyMismatchException
     */
    public function testCompWithDifferentCurrencies()
    {
        $this->calculator->comp(new Money('1', 'EUR'), new Money('2', 'USD'));
    }


    protected function stubIsZeroForFirst($firstAmount, $secondAmount)
    {
        $this->math
            ->method($this->equalTo('isZero'))
            ->with($this->logicalXor($this->equalTo($firstAmount), $this->equalTo($secondAmount)))
            ->will($this->returnCallback(function($arg) use ($firstAmount) {
                return $arg === $firstAmount;
            }))
        ;
    }

    protected function assertCallsFactory($amount, $currency)
    {
        $this->factory->expects($this->once())
            ->method('create')
            ->with($amount, $currency)
            ->willReturn($this->factoryReturn)
        ;
    }

    protected function assertReturnsFactoryResult($return)
    {
        $this->assertSame($this->factoryReturn, $return);
    }
}
