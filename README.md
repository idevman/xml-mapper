# XML Mapper

Create a mapped array from XML data using alias from MAP as laravel plugin.

## Installation

#### Dependencies:

* [Laravel 5.5+](https://github.com/laravel/laravel)

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
    <item number="1">Table</item>
    <item number="2">Chair</item>
    <item number="3">Door</item>
    <item number="4">Window</item>
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