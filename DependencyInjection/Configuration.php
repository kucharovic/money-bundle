<?php declare(strict_types=1);

namespace JK\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Intl\Intl;
use NumberFormatter;
use ResourceBundle;

/**
 * This class contains the configuration information for the bundle.
 *
 * @author Jakub Kucharovic <jakub@kucharovic.cz>
 */
class Configuration implements ConfigurationInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder('jk_money');
		$rootNode = $treeBuilder->getRootNode();

		$rootNode
			->children()
				->scalarNode('currency')->defaultValue('USD')->end()
			->end();
		;

		return $treeBuilder;
	}
}
