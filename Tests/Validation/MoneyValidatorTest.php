<?php

namespace Maba\Component\Monetary\Tests;

use Maba\Component\Math\Exception\InvalidNumberException;
use Maba\Component\Math\MathInterface;
use Maba\Component\Math\NumberValidatorInterface;
use Maba\Component\Monetary\Information\MoneyInformationProviderInterface;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\Validation\MoneyValidator;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;
use \PHPUnit_Framework_MockObject_Builder_MethodNameMatch as MethodNameMatch;

class MoneyValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockObject|MethodNameMatch
     */
    protected $math;

    /**
     * @var MockObject|MethodNameMatch
     */
    protected $informationProvider;

    /**
     * @var MockObject|MethodNameMatch
     */
    protected $numberValidator;

    /**
     * @var MoneyValidator
     */
    protected $validator;


    public function setUp()
    {
        /** @var MathInterface|MockObject|MethodNameMatch $math */
        $this->math = $math = $this->getMock('Maba\Component\Math\MathInterface');
        /** @var MoneyInformationProviderInterface|MockObject|MethodNameMatch $informationProvider */
        $this->informationProvider = $informationProvider = $this->getMock(
            'Maba\Component\Monetary\Information\MoneyInformationProviderInterface'
        );
        /** @var NumberValidatorInterface|MockObject|MethodNameMatch $numberValidator */
        $this->numberValidator = $numberValidator = $this->getMock('Maba\Component\Math\NumberValidatorInterface');

        $this->validator = new MoneyValidator($math, $informationProvider, $numberValidator);

        $math->method($this->equalTo('isZero'))->will($this->returnCallback(function($arg) {
            return $arg === '0';
        }));
        $math->method($this->equalTo('isEqual'))->will($this->returnCallback(function($f, $s) {
            return $f === $s;
        }));
        $informationProvider
            ->method($this->equalTo('getSupportedCurrencies'))
            ->will($this->returnValue(array('EUR', 'USD')))
        ;
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\InvalidAmountException
     */
    public function testValidateMoneyWithInvalidAmount()
    {
        $this->numberValidator
            ->method($this->equalTo('validateNumber'))
            ->will($this->throwException(new InvalidNumberException()))
        ;
        $this->validator->validateMoney(new Money('1', 'EUR'));
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\InvalidCurrencyException
     */
    public function testValidateMoneyWithInvalidCurrency()
    {
        $this->validator->validateMoney(new Money('1', 'ZZZ'));
    }

    public function testValidateMoney()
    {
        $this->validator->validateMoney(new Money('1', 'EUR'));
        $this->addToAssertionCount(1);  // does not throw an exception
    }

    public function testValidateMoneyWithZeroAmount()
    {
        $this->validator->validateMoney(new Money('0', null));
        $this->addToAssertionCount(1);  // does not throw an exception
    }
}
