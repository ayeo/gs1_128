GS1 barcode generator
=====================

The library generates GS1 barcode. Currently supported formats: GS1 128.

Install
-------

Note that library requires both GD and imagettftext extensions

```
composer install ayeo/gs1_barcode:1.0.*
```

Usage
-----

The simplest example:

```php
use Ayeo\Barcode;

$builder = new Barcode\Builder();
$builder->output('(10)123456(400)11');
```
This will generate png image using black and white and default font

![barcode](http://q.i-systems.pl/file/fa869375.png "Generated barcode")


Custom barcode:

```php
use Ayeo\Barcode;

$builder = new Barcode\Builder();
$builder->setBarcodeType('gs1-128');
$builder->setFilename('barcode.png');
$builder->setImageFormat('png');
$builder->setWidth(500);
$builder->setHeight(150);
$builder->setFontPath('FreeSans.ttf');
$builder->setFontSize(15);
$builder->setBackgroundColor(255, 255, 255);
$builder->setPaintColor(0, 0, 0);
```

Finally, you can use the output method to stream the image directly to the web browser.

```php
$builder->output('(10)123456(400)11');
```

If you want to save the image file, you can use the saveImage method instead.

```php
$builder->saveImage('(10)123456(400)11');
```

Acctualy generate the dame barcode becouse all params all set to default values. This shows only
available settings

Fluent interface is welcome

```php
use Ayeo\Barcode;

Barcode\Builder::build()->setWidth(600)->setBackgroundColor(100, 100, 100)->output('(10)123456(400)11');
```

Additional info
---------------

- Supported image formats: png, jpg
- Barcode must be valid GS1 barcode

Contributing
------------

Everyone is welcome, feel free to join

Supported identifiers
---------------------

The goal is to support all existing gs1 application identifiers but at the moment I have added
only those I needed.
Feel free to add or request some. Full list is [here](http://www.databar-barcode.info/application-identifiers)

|Code       |Name                                        |Min length |Max length |
|-----------|--------------------------------------------|-----------|-----------|
|00         |SERIAL SHIPPING CONTAINER CODE              |18         |18         |
|01         |GLOBAL TRADE ITEM NUMBER                    |14         |14         |
|02         |ITEM TRADE ITEM NUMBER                      |14         |14         |
|10         |BATCH NUMBER                                |1          |20         |
|12         |PAYMENT DATE  (YYMMDD)                      |6          |6          |
|15         |BEST BEFORE DATE (YYMMDD)                   |6          |6          |
|37         |NUMBER OF UNITS CONTAINED                   |1          |8          |
|3301       |CONTAINER GROSS WEIGHT (KG)                 |6          |6          |
|390(n)     |AMOUNT PAYABLE - SINGLE MONETARY AREA       |1          |15         |
|400        |CUSTOMER PURCHASE ORDER NUMBER              |1          |30         |
|415        |GLOBAL LOCATION NUMBER OF THE INVOICE PARTY |13         |13         |
|8020       |PAYMENT SLIP REFERENCE NUMBER               |1          |25         |
|96         |COMPANY INTERNAL INFORMATION                |1          |30         |
