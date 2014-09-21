<?php


namespace Maba\Component\Monetary\Formatting;


class FormattingContext
{
    /**
     * @var string
     */
    protected $default = '';

    /**
     * @var string|null
     */
    protected $template = null;

    /**
     * @var string
     */
    protected $decimalPoint = '.';

    /**
     * @var string
     */
    protected $thousandsSeparator = '';

    /**
     * @var int|null
     */
    protected $precision = null;

    /**
     * @var boolean
     */
    protected $currencyIncludedOnZero = true;

    /**
     * @param string $thousandsSeparator
     * @return $this
     */
    public function setThousandsSeparator($thousandsSeparator)
    {
        $this->thousandsSeparator = $thousandsSeparator;
        return $this;
    }

    /**
     * @return string
     */
    public function getThousandsSeparator()
    {
        return $this->thousandsSeparator;
    }

    /**
     * @param string $template %amount% for amount, %currency% for currency
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $default
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $decimalPoint
     * @return $this
     */
    public function setDecimalPoint($decimalPoint)
    {
        $this->decimalPoint = $decimalPoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getDecimalPoint()
    {
        return $this->decimalPoint;
    }

    /**
     * @param int|null $precision
     * @return $this
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param boolean $currencyIncludedOnZero
     * @return $this
     */
    public function setCurrencyIncludedOnZero($currencyIncludedOnZero)
    {
        $this->currencyIncludedOnZero = $currencyIncludedOnZero;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCurrencyIncludedOnZero()
    {
        return $this->currencyIncludedOnZero;
    }

}