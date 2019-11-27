<?php declare(strict_types=1);

namespace JK\MoneyBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use JK\MoneyBundle\DependencyInjection\JKMoneyExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class JKMoneyExtensionTest extends TestCase
{
	public function testParameterNotFoundException()
	{
		$this->expectException(ParameterNotFoundException::class);
		$container = new ContainerBuilder();
		$loader = new JKMoneyExtension();
		$config = [];
		$loader->load(array($config), $container);
	}

	public function testInvalidConfigurationException()
	{
		$this->expectException(InvalidConfigurationException::class);
		$container = new ContainerBuilder();
		$container->setParameter('kernel.default_locale', 'xx');
		$loader = new JKMoneyExtension();
		$config = [];
		$loader->load(array($config), $container);
	}

	public function testLoadFormConfiguration()
	{
		if (false === interface_exists('Twig\Extension\ExtensionInterface')) {
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
