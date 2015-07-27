PHP Library for Operations with Money
====

Instead of storing prices, profit and any other amount of money in float or integer variable, use Money value object
instead. It groups amount with currency, as amount without currency does not give much information.
Furthermore, this library uses [math library](https://github.com/mariusbalcytis/math) for arbitrary precision mathematics.
Thus, it uses strings for storing money units.

### Why not floats?

 - Floats are not reliable for making operations with money as they can loose precision and cents can get lost.

### Why not integers?

 - When max integer value is reached, crazy things can happen: it can be truncated or even get negative.
 These types of errors can be critical, especially when working with money.
 On 32bit systems (and any system on Windows) max int value is 2147483647. As this is enough for most cases, some
 currencies are relatively very small compared to others (for example, 2,147,483,647.00 BYR is about 150,000.00 EUR).
 - There might be cases, where money should be calculated or even saved without rounding to smallest available units.
 For example, very small commissions (parts of cent), which can add to a big amount when large number of them are
 added.
 - When storing amount as integer, you must always take into account the currency divisor
 (smallest available unit in that currency) before outputing the result or any other operation. For example, most currencies
 have cents as their smallest unit while smallest unit for Bahraini Dinar is 0.001 and Japanese Yen has no cents at all.
 Alternatively, if you always store units as cents, you cannot represent smallest units of some currencies.

### Architecture

`Money` is value object - it's immutable. In other words, if you need to change the amount or currency, just create
another `Money` object. The same `Money` object can be referenced in several places, so changing only the fields
of this object could unintentionally change money amount or currency in some other place.

Also, default implementation of `Money` holds no logic - it just contains amount and currency. All operations are
performed by services:

 - Arithmetic and comparison operations - by `MoneyCalculator`, which implements `MoneyCalculatorInterface`.
 - Validation - by `MoneyValidator` and `FinalMoneyValidator`, which implement `MoneyValidatorInterface`.
 `FinalMoneyValidator` asserts that `Money` contains no smaller parts than smallest currency unit.
 - Creation of `Money` objects - by `MoneyFactory`, which implements `MoneyFactoryInterface`.

#### Why not self-contained logic?

This approach lets you change the logic and the class used to represent money easily.

For example, you can use some custom `Money` class - this library makes operations on objects with `MoneyInterface`.
As `MoneyFactoryInterface` is always used to create new entities, you can change result class of any `Money` operation:

```php
// ...
// lots of code using MyMoney class
// ...

class MyMoney implements MoneyInterface
{
    // ...

    public function getAmount()
    {
        // ...
    }

    public function getCurrency()
    {
        // ...
    }
}

class MyMoneyFactory implements MoneyFactoryInterface
{
    // create instances of MyMoney here
}

// construct $calculator with instance of MyMoneyFactory

$result = $calculator->add(new MyMoney('1', 'EUR'), new Money('2.12', 'EUR'));  // you can mix classes, too
// $result is instance of MyMoney now
```

Another example - implementation of `MoneyCalculatorInterface`, which lets you to add `Money` objects with different
currencies, automatically making currency exchange operations.

## Installing

```shell
composer require maba/monetary
```

## Usage

```php
use Maba\Component\Math\BcMath;
use Maba\Component\Math\Math;
use Maba\Component\Math\NumberFormatter;
use Maba\Component\Math\NumberValidator;
use Maba\Component\Monetary\Factory\MoneyFactory;
use Maba\Component\Monetary\Formatting\FormattingContext;
use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\Information\MoneyInformationProvider;
use Maba\Component\Monetary\Validation\MoneyValidator;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyCalculator;

// set up dependencies. I would really suggest to use DIC here
$math = new Math(new BcMath());
$informationProvider = new MoneyInformationProvider();
$factory = new MoneyFactory($math, new MoneyValidator($math, $informationProvider, new NumberValidator()));
$calculator = new MoneyCalculator($math, $factory, $informationProvider);

// make math operations on Money objects
$result = $calculator->ceil(
    $calculator->mul(
        $calculator->mul(
            $calculator->add(new Money('12.23', 'EUR'), new Money('32.12', 'EUR')),
            879134421.2183
        ),
        12.33
    )
);

// compare Money objects
if ($calculator->isGt($result, $factory->createZero())) {
    // format Money objects as strings
    $formatter = new MoneyFormatter(
        $calculator,
        $informationProvider,
        new NumberFormatter($math),
        array('EUR' => '€'),        // optional - symbols to replace currency codes
        '%currency%%amount%'        // optional - custom template
    );

    // set up context. $context argument is optional and needed only if defaults need to be changed
    $context = new FormattingContext();
    $context->setThousandsSeparator(' ');
    // set other formatting options in the context if needed
    echo $formatter->formatMoney($result, $context); // outputs €480 741 910 794.12
}

```

## Running tests

[![Travis status](https://travis-ci.org/mariusbalcytis/monetary.svg?branch=master)](https://travis-ci.org/mariusbalcytis/monetary)
[![Coverage Status](https://coveralls.io/repos/mariusbalcytis/monetary/badge.svg?branch=master&service=github)](https://coveralls.io/github/mariusbalcytis/monetary?branch=master)

```
composer install
vendor/bin/phpunit
```

