<?php

namespace Maba\Component\Monetary;

/**
 * Entity representing money
 *
 * @author Marius Balčytis <m.balcytis@evp.lt>
 */
class Money implements MoneyInterface
{
    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;


    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

}
