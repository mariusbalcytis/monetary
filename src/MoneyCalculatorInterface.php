<?php

namespace Maba\Component\Monetary;

use Maba\Component\Monetary\Exception\CurrencyMismatchException;

interface MoneyCalculatorInterface
{

    /**
     * Negates money
     *
     * @param MoneyInterface $money
     * @return MoneyInterface
     */
    public function negate(MoneyInterface $money);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return MoneyInterface
     * @throws CurrencyMismatchException
     */
    public function add(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return MoneyInterface
     * @throws CurrencyMismatchException
     */
    public function sub(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $money
     * @param MoneyInterface $divisorMoney
     * @param int|null $roundPrecision
     * @return MoneyInterface
     */
    public function divMoney(MoneyInterface $money, MoneyInterface $divisorMoney, $roundPrecision = null);

    /**
     * @param MoneyInterface $money
     * @param string $multiplier
     *
     * @return MoneyInterface
     */
    public function mul(MoneyInterface $money, $multiplier);

    /**
     * @param MoneyInterface $money
     * @param string $divisor
     *
     * @return MoneyInterface
     */
    public function div(MoneyInterface $money, $divisor);

    /**
     * @param MoneyInterface $money
     * @param int $precision
     * @param int $mode
     *
     * @return MoneyInterface
     */
    public function round(MoneyInterface $money, $precision = null, $mode = PHP_ROUND_HALF_UP);

    /**
     * @param MoneyInterface $money
     * @param int $precision
     *
     * @return MoneyInterface
     */
    public function ceil(MoneyInterface $money, $precision = null);

    /**
     * @param MoneyInterface $money
     * @param int $precision
     *
     * @return MoneyInterface
     */
    public function floor(MoneyInterface $money, $precision = null);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return int
     * @throws CurrencyMismatchException
     */
    public function comp(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isGt(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isGte(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isLt(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isLte(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isEqual(MoneyInterface $first, MoneyInterface $second);

    /**
     * @param MoneyInterface $money
     * @return boolean
     */
    public function isNegative(MoneyInterface $money);

    /**
     * @param MoneyInterface $money
     * @return boolean
     */
    public function isPositive(MoneyInterface $money);

    /**
     * @param MoneyInterface $money
     * @return boolean
     */
    public function isZero(MoneyInterface $money);

    /**
     * @param MoneyInterface $money
     * @param int $roundingMode
     * @return string
     */
    public function getCents(MoneyInterface $money, $roundingMode = PHP_ROUND_HALF_UP);

}
