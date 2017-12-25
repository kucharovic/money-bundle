<?php

namespace JK\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Intl\Intl;
use NumberFormatter;

/**
 * This class contains the configuration information for the bundle.
 *
 * @author Jakub Kucharovic <jakub@kucharovic.cz>
 */
class Configuration implements ConfigurationInterface
{
	/** @var string **/
	private $currencyCode;

	/**
	 * @param string locale for currency code
	 */
	public function  __construct($locale)
	{
		$locales = Intl::getLanguageBundle()->getLocales();

		if (false === in_array($locale, $locales)) {
			throw new InvalidConfigurationException("Locale '$locale' is not valid.");
		}

		if (2 == strlen($locale)) {
			// Default US dollars
			$locale .= '_US';
		}

		$formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
		$this->currencyCode = $formatter->getTextAttribute(NumberFormatter::CURRENCY_CODE);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('jk_money');

		$rootNode
			->children()
				->scalarNode('currency')->defaultValue($this->currencyCode)->end()
			->end();
		;

		return $treeBuilder;
	}
}
