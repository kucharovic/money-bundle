[![GitHub Release](https://img.shields.io/github/release/kucharovic/money-bundle.svg?style=flat-square)](https://github.com/kucharovic/money-bundle/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/kucharovic/money-bundle.svg?style=flat-square)](https://packagist.org/packages/kucharovic/money-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

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
$ composer require kucharovic/money-bundle
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
        $bundles = [
            // ...

            new JK\MoneyBundle\JKMoneyBundle(),
        ];

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
     * @var Money
     *
     * @ORM\Embedded(class="Money\Money")
     */
    private $price;

    // ...

    public function __construct()
    {
        $this->price = Money::CZK(0);
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
```

This entity mapping produces following table structure:
```
+---------------------+--------------+------+-----+---------+----------------+
| Field               | Type         | Null | Key | Default | Extra          |
+---------------------+--------------+------+-----+---------+----------------+
| id                  | int(11)      | NO   | PRI | NULL    | auto_increment |
| name                | varchar(255) | NO   |     | NULL    |                |
| price_amount        | varchar(255) | NO   |     | NULL    |                |
| price_currency_code | char(3)      | NO   |     | NULL    |                |
+---------------------+--------------+------+-----+---------+----------------+
```

So it's easy to query database using aggregate functions like `SUM`, `AVG`, etc:

```sql
SELECT MAX(`price_amount`), `price_currency_code`
FROM `product`
GROUP BY `price_currency_code`;
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
