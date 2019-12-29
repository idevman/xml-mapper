# XML Mapper
[![Latest Stable Version](https://poser.pugx.org/idevman/xml-mapper/v/stable)](https://packagist.org/packages/idevman/xml-mapper)
[![Total Downloads](https://poser.pugx.org/idevman/xml-mapper/downloads)](https://packagist.org/packages/idevman/xml-mapper)
[![Latest Unstable Version](https://poser.pugx.org/idevman/xml-mapper/v/unstable)](https://packagist.org/packages/idevman/xml-mapper)
[![License](https://poser.pugx.org/idevman/xml-mapper/license)](https://packagist.org/packages/idevman/xml-mapper)
[![composer.lock](https://poser.pugx.org/idevman/xml-mapper/composerlock)](https://packagist.org/packages/idevman/xml-mapper)

Create a mapped array from XML data using alias from MAP as laravel plugin.

## Installation

#### Dependencies:

* [Laravel 6.2+](https://github.com/laravel/laravel) (v2.0.0)
* [Laravel 5.5+](https://github.com/laravel/laravel) (v1.0.4)

#### Installation:

**-** Require the package via Composer
```bash
composer require idevman/xml-mapper
```

##### Laravel 5.5+

Laravel 5.5+ will autodiscover the package, for older versions add the
following service provider
```php
Idevman\XmlMapper\XmlMapperServiceProvider::class,
```

and alias
```php
'XmlMapper' => 'Idevman\XmlMapper\Support\Facades\XmlMapper',
```

## Usage

To use it lets assume xml content: 

```xml
<note from="Tove" to="Jani">
    <content heading="Reminder" body="Don\'t forget me this weekend!"/>
    <raw>Simple text</raw>
    <item number="1" value="50">Table</item>
    <item number="2" value="40">Chair</item>
    <item number="3" value="30">Door</item>
    <item number="4" value="60">Window</item>
</note>
```

So, load this in `$xml` variable and call liek this:

```php
$response = XmlMapper::mapTo([
    'from' => '/note@from',
    'to' => '/note@to',
    'heading' => '/note/content@heading',
    'body' => '/note/content@body',
    'raw' => '/note/raw',
    'simpleItem' => '/note/item',
    'simpleItemNumber' => '/note/item@number',
    'allItems' => '/note/item[]',
    'allItemsNumber' => '/note/item[]@number',
], $xml);
```

And `$response` will contain

```php
[
  "from" => "Tove"
  "to" => "Jani"
  "heading" => "Reminder"
  "body" => "Don't forget me this weekend!"
  "raw" => "Simple text"
  "simpleItem" => "Table"
  "simpleItemNumber" => "1"
  "allItems" => [
    0 => "Table"
    1 => "Chair"
    2 => "Door"
    3 => "Window"
  ]
  "allItemsNumber" => [
    0 => "1"
    1 => "2"
    2 => "3"
    3 => "4"
  ]
]
```

## Grouping attribute nodes

Lets assuma same XML and load this in `$xml` variable and call like this:

```php
$response = XmlMapper::mapTo([
    'addressing[from,to]' => '/note[@from,@to]',
    'content[header,body]' => '/note/content[@heading,@body]',
    'items[sequence,value]' => '/note/item[][@number,@value]',
], $xml);
```

And `$response` will contain this result:

```php
[
  "addressing" => [
    "from" => "Tove"
    "to" => "Jani"
  ]
  "content" => [
    "header" => "Reminder"
    "body" => "Don't forget me this weekend!"
  ]
  "items" => [
    [
      "sequence" => "1"
      "value" => "50"
    ],
    [
      "sequence" => "2"
      "value" => "40"
    ],
    [
      "sequence" => "3"
      "value" => "30"
    ],
    [
      "sequence" => "4"
      "value" => "60"
    ]
  ]
]
```

Note that labeling change according key array content
