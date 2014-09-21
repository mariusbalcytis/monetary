<?php


namespace Maba\Component\Monetary\Tests;


use Maba\Component\Monetary\Money;

class MoneyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAmount()
    {
        $amount = new \stdClass();
        $money = new Money($amount, null);
        $this->assertSame($amount, $money->getAmount());
    }

    public function testGetCurrency()
    {
        $currency = new \stdClass();
        $money = new Money(null, $currency);
        $this->assertSame($currency, $money->getCurrency());
    }
}
