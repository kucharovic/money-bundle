<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JK\MoneyBundle\Tests\Form\DataTransformer;

use PHPUnit\Framework\TestCase;
use JK\MoneyBundle\Form\DataTransformer\MoneyToLocalizedStringTransformer;
use Money\Money;
use Money\Currency;
use Money\Currencies\BitcoinCurrencies;
use Symfony\Component\Intl\Util\IntlTestHelper;
use Locale;

class MoneyToLocalizedStringTransformerTest extends TestCase
{
    public function dataProvider()
    {
        $data = [
            ['cs_CZ', 'CZK', 2, false, 1599, '15,99', null],
            ['cs_CZ', 'CZK', 2, false, 999999, '9999,99', null],
            ['cs_CZ', 'CZK', 2, true, 999999, '9 999,99', null],
            ['cs_CZ', 'CZK', 1, false, 999990, '9999,9', null],
            ['en_US', 'USD', 2, true, 999999, '9,999.99', null],
            ['en_US', 'USD', 2, true, 999999, '9,999.99', null],
            ['en_US', 'XBT', 8, true, 999999, '0.00999999', new BitcoinCurrencies()],
            ['en_US', 'XBT', 8, true, 1, '0.00000001', new BitcoinCurrencies()],
        ];

        return $data;
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDataTransform($locale, $currency, $scale, $grouping, $input, $output, $currencies)
    {
        IntlTestHelper::requireFullIntl($this, false);

        Locale::setDefault($locale);
        $transformer = new MoneyToLocalizedStringTransformer($currency, $scale, $grouping, $currencies);

        $input = new Money($input, new Currency($currency));

        $this->assertEquals($output, $transformer->transform($input));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDataReverseTransform($locale, $currency, $scale, $grouping, $input, $output, $currencies)
    {
        IntlTestHelper::requireFullIntl($this, false);

        Locale::setDefault($locale);
        $transformer = new MoneyToLocalizedStringTransformer($currency, $scale, $grouping, $currencies);

        $input = new Money($input, new Currency($currency));

        $this->assertEquals($input, $transformer->reverseTransform($output));
    }
}
