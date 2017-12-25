<?php

namespace JK\MoneyBundle\Twig;

use Twig_Extension, Twig_SimpleFilter;
use NumberFormatter, Locale;
use Money\Money;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Formatter\DecimalMoneyFormatter;

/**
 * This class contains the configuration information for the bundle.
 *
 * @author Jakub Kucharovic <jakub@kucharovic.cz>
 */
class MoneyExtension extends Twig_Extension
{
	const FORMAT_CURRENCY = true;
	const FORMAT_DECIMAL  = false;

	const GROUPING_USED = true;
	const GROUPING_NONE = false;

	private $locale;

	public function __construct($locale)
	{
		$this->locale = $locale;
	}

	public function getFilters()
	{
		return [
			new Twig_SimpleFilter('money', [$this, 'moneyFilter']),
		];
	}

	public function moneyFilter(Money $money, $scale = 2, $groupingUsed = self::GROUPING_USED, $format = self::FORMAT_CURRENCY)
	{
		$noFormatter = new NumberFormatter($this->locale, $format === self::FORMAT_CURRENCY ? NumberFormatter::CURRENCY : NumberFormatter::DECIMAL);
		$noFormatter->setAttribute(NumberFormatter::GROUPING_USED, $groupingUsed);
		$noFormatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $scale);

		$intlFormatter = new IntlMoneyFormatter($noFormatter, new ISOCurrencies());

		// replace non-break spaces with ascii spaces
		return str_replace("\xc2\xa0", "\x20", $intlFormatter->format($money));
	}
}
