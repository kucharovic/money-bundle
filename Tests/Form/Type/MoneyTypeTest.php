<?php

namespace JK\MoneyBundle\Tests\Form\Type;

use Money\Money;
use JK\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Intl\Util\IntlTestHelper;
use Locale;

/**
 * Class MoneyTypeTest.
 *
 * @author Jakub Kucharovic <jakub@kucharovic.cz>
 */
class MoneyTypeTest extends TypeTestCase
{
    protected function setUp()
    {
        // we test against different locales, so we need the full
        // implementation
        IntlTestHelper::requireFullIntl($this, false);

        parent::setUp();
    }

    protected function getExtensions()
    {
        return [
            new PreloadedExtension([
                new MoneyType('CZK')
            ], [])
        ];
    }

    public function testPassMoneyPatternToView()
    {
        Locale::setDefault('en_US');

        $view = $this->factory->create(MoneyType::class)->createView();

        $this->assertSame('CZK {{ widget }}', $view->vars['money_pattern']);
    }

    public function testPassLocalizedMoneyPatternToView()
    {
        Locale::setDefault('cs_CZ');

        $view = $this->factory->create(MoneyType::class)->createView();

        $this->assertSame('{{ widget }} Kč', $view->vars['money_pattern']);
    }

    public function testPassOverridenMoneyPatternToView()
    {
        $view = $this->factory->create(MoneyType::class, null, ['currency' => 'EUR'])->createView();

        $this->assertSame('€ {{ widget }}', $view->vars['money_pattern']);
    }
}