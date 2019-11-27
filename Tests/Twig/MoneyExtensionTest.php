<?php declare(strict_types=1);

namespace JK\MoneyBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use JK\MoneyBundle\Twig\MoneyExtension;
use Symfony\Component\Intl\Util\IntlTestHelper;
use Money\Money;
use Money\Currency;
use Twig\TwigFilter;
use Locale;

class MoneyExtensionTest extends TestCase
{
	public function setUp(): void
	{
		IntlTestHelper::requireFullIntl($this, false);
		parent::setUp();
	}
	public function testGetFilters()
	{
		$extension = new MoneyExtension('cs_CZ');

		$this->assertEquals(
			[new TwigFilter('money', [$extension, 'moneyFilter'])],
			$extension->getFilters()
		);
	}

	public function dataProvider()
	{
		return [
			['cs_CZ', 'CZK', 2, MoneyExtension::GROUPING_USED, MoneyExtension::FORMAT_CURRENCY, 1599, '15,99 Kč'],
			['cs_CZ', 'CZK', 2, MoneyExtension::GROUPING_USED, MoneyExtension::FORMAT_DECIMAL, 1599, '15,99'],
			['cs_CZ', 'EUR', 2, MoneyExtension::GROUPING_USED, MoneyExtension::FORMAT_CURRENCY, 1599, '15,99 €'],
			['en_US', 'EUR', 2, MoneyExtension::GROUPING_USED, MoneyExtension::FORMAT_CURRENCY, 1599, '€15.99'],
			['en_US', 'EUR', 2, MoneyExtension::GROUPING_USED, MoneyExtension::FORMAT_CURRENCY, 151599, '€1,515.99'],
			['cs_CZ', 'CZK', 2, MoneyExtension::GROUPING_USED, MoneyExtension::FORMAT_CURRENCY, 151599, '1 515,99 Kč'],
			['cs_CZ', 'CZK', 2, MoneyExtension::GROUPING_NONE, MoneyExtension::FORMAT_CURRENCY, 151599, '1515,99 Kč'],
			['cs_CZ', 'CZK', 1, MoneyExtension::GROUPING_NONE, MoneyExtension::FORMAT_CURRENCY, 151590, '1515,9 Kč'],
			['cs_CZ', 'CZK', 0, MoneyExtension::GROUPING_NONE, MoneyExtension::FORMAT_DECIMAL, 151590, '1516'],
		];
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function testMoneyFilter($locale, $currency, $scale, $grouping, $format, $input, $output)
	{
		Locale::setDefault($locale);
		$extension = new MoneyExtension($locale);
		$input = new Money($input, new Currency($currency));
		$this->assertEquals(
			$extension->moneyFilter($input, $scale, $grouping, $format),
			$output
		);
	}
}
