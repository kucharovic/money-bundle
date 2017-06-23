<?php

namespace JK\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
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
