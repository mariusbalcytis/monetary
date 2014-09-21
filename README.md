PHP Math library
====

PHP library for money operations.

![Travis status](https://travis-ci.org/mariusbalcytis/monetary.svg?branch=master)

The need
----

 - When working with money, currency is always important. To keep track of it money should be held like scalar value,
 not like separate amount and currency variables
 - Floats are not reliable for making operations with money as they can loose precision and cents can get lost
 - When max float value is reached, it's simply truncated. These types of errors can be critical, especially when
 working with money

Usage
----

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

To avoid issues, never change Money objects (their internal state) after construction as the same object can be
referenced in several places without intention to synchronize these values.

As money is not modifiable, each operation with money returns new Money object, created by `MoneyFactoryInterface`.

As you can create `Money` objects yourself (they are just entities with no external dependencies),
`MoneyFactoryInterface` is used to be able to use some other entities with this library. For example:

```php
// ...
// lots of code using MyMoney class
// ...

class MyMoney implements MoneyInterface
{
    // ...
}

class MyMoneyFactory implements MoneyFactoryInterface
{
    // create instances of MyMoney here
}

// construct $calculator with instance of MyMoneyFactory

$result = $calculator->add($money1, $money2);
// $result is instance of MyMoney now
```