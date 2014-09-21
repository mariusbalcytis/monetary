<?php


namespace Maba\Component\Monetary;


interface MoneyInterface
{
    /**
     * @return string decimal
     */
    public function getAmount();

    /**
     * Currency can be null if amount is zero, as it usually does not matter in that case
     *
     * @return string|null
     */
    public function getCurrency();
}
