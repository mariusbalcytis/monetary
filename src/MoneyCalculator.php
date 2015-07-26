<?php

namespace Maba\Component\Monetary;

use Maba\Component\Math\MathInterface;
use Maba\Component\Monetary\Factory\MoneyFactoryInterface;
use Maba\Component\Monetary\Information\MoneyInformationProviderInterface;
use Maba\Component\Monetary\Exception\CurrencyMismatchException;

class MoneyCalculator implements MoneyCalculatorInterface
{
    protected $math;
    protected $factory;
    protected $informationProvider;

    public function __construct(
        MathInterface $math,
        MoneyFactoryInterface $factory,
        MoneyInformationProviderInterface $informationProvider
    ) {
        $this->math = $math;
        $this->factory = $factory;
        $this->informationProvider = $informationProvider;
    }

    /**
     * Negates money
     *
     * @param MoneyInterface $money
     * @return MoneyInterface
     */
    public function negate(MoneyInterface $money)
    {
        if ($this->isZero($money)) {
            return $money;
        } else {
            return $this->mul($money, '-1');
        }
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return MoneyInterface
     * @throws CurrencyMismatchException
     */
    public function add(MoneyInterface $first, MoneyInterface $second)
    {
        return $this->factory->create(
            $this->math->add($first->getAmount(), $second->getAmount()),
            $this->resolveCurrency($first, $second)
        );
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return MoneyInterface
     * @throws CurrencyMismatchException
     */
    public function sub(MoneyInterface $first, MoneyInterface $second)
    {
        return $this->factory->create(
            $this->math->sub($first->getAmount(), $second->getAmount()),
            $this->resolveCurrency($first, $second)
        );
    }

    /**
     * @param MoneyInterface $money
     * @param MoneyInterface $divisorMoney
     * @param int|null $roundPrecision
     * @return MoneyInterface
     */
    public function divMoney(MoneyInterface $money, MoneyInterface $divisorMoney, $roundPrecision = null)
    {
        $this->resolveCurrency($money, $divisorMoney);
        $result = $this->math->div($money->getAmount(), $divisorMoney->getAmount());
        if ($roundPrecision !== null) {
            $result = $this->math->round($result, $roundPrecision);
        }
        return $result;
    }

    /**
     * @param MoneyInterface $money
     * @param string $multiplier
     *
     * @return MoneyInterface
     */
    public function mul(MoneyInterface $money, $multiplier)
    {
        return $this->factory->create(
            $this->math->mul($money->getAmount(), $multiplier),
            $money->getCurrency()
        );
    }

    /**
     * @param MoneyInterface $money
     * @param string $divisor
     *
     * @return MoneyInterface
     */
    public function div(MoneyInterface $money, $divisor)
    {
        return $this->factory->create(
            $this->math->div($money->getAmount(), $divisor),
            $money->getCurrency()
        );
    }

    /**
     * @param MoneyInterface $money
     * @param int $precision
     * @param int $mode
     *
     * @return MoneyInterface
     */
    public function round(MoneyInterface $money, $precision = null, $mode = PHP_ROUND_HALF_UP)
    {
        if ($precision === null) {
            $precision = $this->informationProvider->getDefaultPrecision($money->getCurrency());
        }
        return $this->factory->create(
            $this->math->round($money->getAmount(), $precision, $mode),
            $money->getCurrency()
        );
    }

    /**
     * @param MoneyInterface $money
     * @param int $precision
     *
     * @return MoneyInterface
     */
    public function ceil(MoneyInterface $money, $precision = null)
    {
        if ($precision === null) {
            $precision = $this->informationProvider->getDefaultPrecision($money->getCurrency());
        }
        return $this->factory->create(
            $this->math->ceil($money->getAmount(), $precision),
            $money->getCurrency()
        );
    }

    /**
     * @param MoneyInterface $money
     * @param int $precision
     *
     * @return MoneyInterface
     */
    public function floor(MoneyInterface $money, $precision = null)
    {
        if ($precision === null) {
            $precision = $this->informationProvider->getDefaultPrecision($money->getCurrency());
        }
        return $this->factory->create(
            $this->math->floor($money->getAmount(), $precision),
            $money->getCurrency()
        );
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return int
     * @throws CurrencyMismatchException
     */
    public function comp(MoneyInterface $first, MoneyInterface $second)
    {
        $this->resolveCurrency($first, $second);
        return $this->math->comp($first->getAmount(), $second->getAmount());
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isGt(MoneyInterface $first, MoneyInterface $second)
    {
        $this->resolveCurrency($first, $second);
        return $this->math->isGt($first->getAmount(), $second->getAmount());
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isGte(MoneyInterface $first, MoneyInterface $second)
    {
        $this->resolveCurrency($first, $second);
        return $this->math->isGte($first->getAmount(), $second->getAmount());
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isLt(MoneyInterface $first, MoneyInterface $second)
    {
        $this->resolveCurrency($first, $second);
        return $this->math->isLt($first->getAmount(), $second->getAmount());
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isLte(MoneyInterface $first, MoneyInterface $second)
    {
        $this->resolveCurrency($first, $second);
        return $this->math->isLte($first->getAmount(), $second->getAmount());
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     *
     * @return boolean
     * @throws CurrencyMismatchException
     */
    public function isEqual(MoneyInterface $first, MoneyInterface $second)
    {
        $this->resolveCurrency($first, $second);
        return $this->math->isEqual($first->getAmount(), $second->getAmount());
    }

    /**
     * @param MoneyInterface $money
     * @return boolean
     */
    public function isNegative(MoneyInterface $money)
    {
        return $this->math->isNegative($money->getAmount());
    }

    /**
     * @param MoneyInterface $money
     * @return boolean
     */
    public function isPositive(MoneyInterface $money)
    {
        return $this->math->isPositive($money->getAmount());
    }

    /**
     * @param MoneyInterface $money
     * @return boolean
     */
    public function isZero(MoneyInterface $money)
    {
        return $this->math->isZero($money->getAmount());
    }

    /**
     * @param MoneyInterface $money
     * @param int $roundingMode
     * @return string
     */
    public function getCents(MoneyInterface $money, $roundingMode = PHP_ROUND_HALF_UP)
    {
        return $this->math->round($this->math->mul($money->getAmount(), 100), 0, $roundingMode);
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     * @return string
     * @throws CurrencyMismatchException
     */
    protected function resolveCurrency(MoneyInterface $first, MoneyInterface $second)
    {
        if ($this->isZero($first)) {
            return $second->getCurrency();
        } elseif ($this->isZero($second)) {
            return $first->getCurrency();
        }

        if ($first->getCurrency() !== $second->getCurrency()) {
            throw new CurrencyMismatchException(sprintf(
                'Operand currency doesn\'t match (%s, %s)',
                $first->getCurrency(),
                $second->getCurrency()
            ));
        }

        return $first->getCurrency();
    }

}
