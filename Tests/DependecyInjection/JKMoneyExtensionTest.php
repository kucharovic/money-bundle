<?php

namespace JK\MoneyBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use JK\MoneyBundle\DependencyInjection\JKMoneyExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JKMoneyExtensionTest extends TestCase
{
    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException
     */
    public function testParameterNotFoundException()
    {
        $container = new ContainerBuilder();
        $loader = new JKMoneyExtension();
        $config = [];
        $loader->load(array($config), $container);
    }

    public function testLoadConfiguration()
    {
        $container = new ContainerBuilder();
        $loader = new JKMoneyExtension();
        $container->setParameter('locale', 'z');
        $config = [];
        $loader->load(array($config), $container);
        $this->assertTrue($container->hasDefinition('JK\MoneyBundle\Form\Type\MoneyType'));
        $this->assertTrue($container->hasDefinition('JK\MoneyBundle\Twig\MoneyExtension'));
    }
}
