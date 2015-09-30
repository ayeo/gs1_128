GS1 barcode generator
=====================

The library generates GS1 barcode. Currently supported formats: GS1 128. Code is under development so it may change 
radically. If you found an issue just let me know. Library generates identical barcodes like: 
http://example.barcodephp.com/html/BCGgs1128.php

Usage
-----

The simplest example:

```php
$builder = new Barcode\Builder();
$builder->output('(10)123456(400)11');
```
This will generate png image using black and white and default font

![barcode](http://q.i-systems.pl/file/fa869375.png "Generated barcode")

 
Custom barcode:

```php
$builder = new Barcode\Builder();
$builder->setBarcodeType('gs1-128');
$builder->setFilename('barcode.png');
$builder->setImageFromat('png');
$builder->setWidth(500);
$builder->setHeight(150);
$builder->setFontPath('FreeSans.ttf');
$builder->setFontSize(15);
$builder->setBackgroundColor(255, 255, 255);
$builder->setPaintColor(0, 0, 0);
$builder->output('(10)123456(400)11');
```

Acctualy generate the dame barcode becouse all params all set to default values. This shows only available settings

Fluent interface is welcome

```php
Barcode\Builder::build()->setWidth(600)->setBackgroundColor(100, 100, 100)->output('(10)123456(400)11');
```

Additional info
---------------

- Supported image formats: png, jpg
- Barcode must be valid GS1 barcode

Contributing
------------

Everyone is welcome, feel free to join
