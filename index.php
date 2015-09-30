<?php
use Ayeo\Barcode;

require_once('vendor/autoload.php');

$builder = new Barcode\Builder();
$builder->setBarcodeType('gs1-128');
$builder->setFilename('barcode.png');
$builder->setImageFromat('png');
$builder->setWidth(500);
$builder->setHeight(150);
//$builder->setFontPath('FreeSans.ttf');
$builder->setFontSize(15);
$builder->setBackgroundColor(255, 255, 255);
$builder->setPaintColor(0, 0, 0);
$builder->output('(10)123456(400)11');

//Barcode\Builder::build()->setWidth(600)->setBackgroundColor(100, 100, 100)->output('(10)123456(400)11');
