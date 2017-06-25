# JKMoneyBundle

This bundle provides integration for [Money](https://github.com/moneyphp/money) library in your Symfony
project. Features include:

 - Automatically add Doctrine mappings (use Doctrine embeddable objects)
 - Customized FormType
 - Twig extension

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kucharovic/money-bundle "^1.0"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new JK\MoneyBundle\JKMoneyBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Configuration

By default, bundle load your application locale and define it's currency code as default. You can override it:
```yaml
# app/config/config.yml

jk_money:
    currency: USD
```


## Usage

### Entity

```php
// src/AppBundle/Entity/Proudct.php

use Doctrine\ORM\Mapping as ORM;
use Money\Money;

// ...
class Product
{
    // ...

    /**
     * @var \Money\Money
     *
     * @ORM\Embedded(class="Money\Money")
     */
    private $price;

    // ...

    public function __construct()
    {
        $this->price = Money::CZK(0);
    }

    /**
     * Set price
     *
     * @param \Money\Money $price
     *
     * @return Product
     */
    public function setPrice(\Money\Money $price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return \Money\Money
     */
    public function getPrice()
    {
        return $this->price;
    }
```

### Form

| Option  | Type | Default |
| ------------- | ------------- |---- |
| currency  | `string`  | your application locale currency |
| grouping  | `boolean`  | `false` |
| scale  | `integer`  | 2 |

```php
// src/AppBundle/Entity/Proudct.php

// ...
use JK\MoneyBundle\Form\Type\MoneyType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price', MoneyType::class)
        ;
    }
```

### Twig templates

```html
<!-- 1 599,90 Kč -->
Formated with czech locale {{ product.price|money }}<br>
<!-- 1599,9 -->
You can also specify scale, grouping and hide currency symbol {{ product.price|money(1, false, false) }
```