<?php

namespace Maba\Component\Monetary\Tests;

use Maba\Component\Math\MathInterface;
use Maba\Component\Monetary\Exception\InvalidAmountException;
use Maba\Component\Monetary\Information\MoneyInformationProviderInterface;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\Validation\FinalMoneyValidator;
use Maba\Component\Monetary\Validation\MoneyValidatorInterface;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;
use \PHPUnit_Framework_MockObject_Builder_MethodNameMatch as MethodNameMatch;

class FinalMoneyValidatorTest extends \PHPUnit_Framework_TestCase
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
    protected $baseValidator;

    /**
     * @var FinalMoneyValidator
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
        /** @var MoneyValidatorInterface|MockObject|MethodNameMatch $baseValidator */
        $this->baseValidator = $baseValidator = $this->getMock(
            'Maba\Component\Monetary\Validation\MoneyValidatorInterface'
        );

        $this->validator = new FinalMoneyValidator($baseValidator, $math, $informationProvider);

        $math->method($this->equalTo('round'))->will($this->returnCallback(function($n) {
            list($n) = explode('.', $n, 2);
            return $n;
        }));
        $math->method($this->equalTo('isEqual'))->will($this->returnCallback(function($f, $s) {
            return $f === $s;
        }));
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\InvalidAmountException
     */
    public function testValidateMoneyWithInvalidBase()
    {
        $money = new Money('1', 'EUR');
        $this->baseValidator->expects($this->once())
            ->method('validateMoney')
            ->with($money)
            ->will($this->throwException(new InvalidAmountException()))
        ;
        $this->validator->validateMoney($money);
    }

    /**
     * @expectedException \Maba\Component\Monetary\Exception\InvalidAmountException
     */
    public function testValidateMoneyWithInvalidPrecision()
    {
        $this->validator->validateMoney(new Money('1.1', 'EUR'));
    }

}
