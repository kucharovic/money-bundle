<?php

namespace JK\MoneyBundle\Form\DataTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Money\Exception\ParserException;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Currency;
use Money\Money;
use NumberFormatter, Locale;

/**
 * Transforms between a normalized format and a localized money string.
 *
 * @author Jakub Kucharovic <jakub@kucharovic.cz>
 */
class MoneyToLocalizedStringTransformer extends NumberToLocalizedStringTransformer
{
    /** @var string ISO currency code **/
    private $currencyCode;
    /** @var \Money\Parser\DecimalMoneyParser **/
    private $moneyParser;
    /** @var \NumberFormatter **/
    private $numberFormatter;

    /**
     * @param string $currencyCode ISO currency code
     * @param int    $scale
     * @param bool   $grouping
     */
    public function __construct($currencyCode, $scale, $grouping)
    {
        $this->numberFormatter = new NumberFormatter(Locale::getDefault(), NumberFormatter::DECIMAL);
        $this->moneyParser = new DecimalMoneyParser(new ISOCurrencies());
        $this->currencyCode = $currencyCode;

        parent::__construct($scale, $grouping);
    }

    /**
     * Transforms a normalized format into a localized money string.
     *
     * @param \Money\Money $value Money object
     *
     * @return string Localized money string
     *
     * @throws TransformationFailedException If the given value is not numeric or
     *                                       if the value can not be transformed.
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        $moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return parent::transform($moneyFormatter->format($value));
    }

    /**
     * Transforms a localized money string into a normalized format.
     *
     * @param string $value Localized money string
     *
     * @return \Money\Money Money object
     *
     * @throws TransformationFailedException If the given value is not a string
     *                                       or if the value can not be transformed.
     */
    public function reverseTransform($value)
    {
        $value = parent::reverseTransform($value);

        try {
            $money = $this->moneyParser->parse(strval($value), $this->currencyCode);
            return $money;
        } catch (ParserException $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }
}
