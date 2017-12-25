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

	/**
	 * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
	 */
	public function testInvalidConfigurationException()
	{
		$container = new ContainerBuilder();
		$container->setParameter('kernel.default_locale', 'xx');
		$loader = new JKMoneyExtension();
		$config = [];
		$loader->load(array($config), $container);
	}

	public function testLoadFormConfiguration()
	{
		if (false === interface_exists('Twig_ExtensionInterface')) {
			$this->markTestSkipped('Package `twig/twig` is not available.');
		}
		$container = new ContainerBuilder();
		$container->setParameter('kernel.default_locale', 'cs');
		$loader = new JKMoneyExtension();
		$config = [];
		$loader->load(array($config), $container);
		$this->assertTrue($container->hasDefinition('JK\MoneyBundle\Form\Type\MoneyType'));
	}

	public function testLoadTwigConfiguration()
	{
		if (false === interface_exists('Symfony\Component\Form\FormInterface')) {
			$this->markTestSkipped('Package `symfony/form` is not available.');
		}        
		$container = new ContainerBuilder();
		$container->setParameter('kernel.default_locale', 'cs');
		$loader = new JKMoneyExtension();
		$config = [];
		$loader->load(array($config), $container);
		$this->assertTrue($container->hasDefinition('JK\MoneyBundle\Twig\MoneyExtension'));
	}    
}
