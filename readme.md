GS1 barcode generator
=====================

The library generates GS1 barcode. Currently supported formats: GS1 128.

Install
-------

Note that library requires both GD and Free Type extensions installed

```
composer require ayeo/gs1_barcode:1.0.4
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

Both output() and saveImage() methods has additional boolean parameters to determine if label should be included on 
the print. Default value is true.

```php
$builder->output('(10)123456(400)11', $withLabel = false);
$builder->saveImage('(10)123456(400)11', $withLabel = false);
```

Actually generate the same barcode because all params all set to default values. This shows only
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

Everyone is welcome, feel free to join. There is Dockerfile included for ease of developemnt. The image consist of
php5.6 with GD and FreeType extensions. It also contains composer and xdebug. To build the image locally use

```
docker build -t php-gs1 .
```

Supported identifiers
---------------------

The goal is to support all existing gs1 application identifiers but at the moment I have added
only those I needed.
Feel free to add or request some. Full list is [here](http://www.databar-barcode.info/application-identifiers)

| Code |                                                                 Name                                                                | Min length | Max length |
|:----:|:-----------------------------------------------------------------------------------------------------------------------------------|:----------:|:----------:|
| 00   | Serial Shipping Container Code (SSCC-18)                                                                                            | 18         | 18         |
| 01   | Global Trade Item Number (GTIN)                                                                                                     | 14         | 14         |
| 02   | GTIN of Contained Trade Items                                                                                                       | 14         | 14         |
| 10   | Batch or Lot Number                                                                                                                 | 1          | 20         |
| 11   | Production Date                                                                                                                     | 6          | 6          |
| 12   | Due Date                                                                                                                            | 6          | 6          |
| 13   | Packaging Date                                                                                                                      | 6          | 6          |
| 15   | Best Before Date                                                                                                                    | 6          | 6          |
| 16   | Sell By Date                                                                                                                        | 6          | 6          |
| 17   | Expiration Date                                                                                                                     | 6          | 6          |
| 20   | Internal Product Variant                                                                                                            | 2          | 2          |
| 21   | Serial Number                                                                                                                       | 1          | 20         |
| 22   | Consumer Product Variant                                                                                                            | 1          | 20         |
| 235  | Third Party Controlled, Serialised Extension of GTIN (TPX)                                                                          | 1          | 28         |
| 240  | Additional Item Identification                                                                                                      | 1          | 30         |
| 241  | Customer Part Number                                                                                                                | 1          | 30         |
| 242  | Made-to-Order Variation Number                                                                                                      | 1          | 6          |
| 243  | Packaging Comnponent Number                                                                                                         | 1          | 20         |
| 250  | Second Serial Number                                                                                                                | 1          | 30         |
| 251  | Reference to Source Entity                                                                                                          | 1          | 30         |
| 253  | Global Document Type Identifier (GDTI)                                                                                              | 14         | 30         |
| 254  | GLN Extension Component                                                                                                             | 1          | 20         |
| 255  | Global Coupon Number (GCN)                                                                                                          | 14         | 25         |
| 30   | Variable Count of Items (variable measure trade item)                                                                               | 1          | 8          |
| 310y | Net Weight in kilograms (variable measure trade item)                                                                               | 6          | 6          |
| 311y | Length or 1st Dimension, in meters (variable measure trade item)                                                                    | 6          | 6          |
| 312y | Width, Diameter, or 2nd Dimension, in meters (variable measure trade item)                                                          | 6          | 6          |
| 313y | Depth, Thickness, Height, or 3rd Dimension, in meters (variable measure trade item)                                                 | 6          | 6          |
| 314y | Area, in square meters (variable measure trade item)                                                                                | 6          | 6          |
| 315y | Net Volume, in liters (variable measure trade item)                                                                                 | 6          | 6          |
| 316y | Net Volume, in cubic meters (variable measure trade item)                                                                           | 6          | 6          |
| 320y | Net Weight, in pounds (variable measure trade item)                                                                                 | 6          | 6          |
| 321y | Length or 1st Dimension, in inches (variable measure trade item)                                                                    | 6          | 6          |
| 322y | Length or 1st Dimension, in feet (variable measure trade item)                                                                      | 6          | 6          |
| 323y | Length, 1st Dimension, in yards (variable measure trade item)                                                                       | 6          | 6          |
| 324y | Width, Diameter, or 2nd Dimension, in inches (variable measure trade item)                                                          | 6          | 6          |
| 325y | Width, Diameter, or 2nd Dimension, in feet (variable measure trade item)                                                            | 6          | 6          |
| 326y | Width, Diameter, or 2nd Dimension, in yards (variable measure trade item)                                                           | 6          | 6          |
| 327y | Depth, Thickness, Height, or 3rd Dimension, in inches (variable measure trade item)                                                 | 6          | 6          |
| 328y | Depth, Thickness, Height, or 3rd Dimension, in feet (variable measure trade item)                                                   | 6          | 6          |
| 329y | Depth, Thickness, Height, or 3rd Dimension, in yards (variable measure trade item)                                                  | 6          | 6          |
| 330y | Logistic Weight, in kilograms                                                                                                       | 6          | 6          |
| 331y | Length, or 1st Dimension, in meters                                                                                                 | 6          | 6          |
| 332y | Width, Diameter, or 2nd Dimension, in meters                                                                                        | 6          | 6          |
| 333y | Depth, Thickness, Height, or 3rd Dimension, in meters                                                                               | 6          | 6          |
| 334y | Area, in square meters                                                                                                              | 6          | 6          |
| 335y | Logistic Volume, in liters                                                                                                          | 6          | 6          |
| 336y | Logistic Volume, in cubic meters                                                                                                    | 6          | 6          |
| 337y | Kilograms per square meter                                                                                                          | 6          | 6          |
| 340y | Logistic Weight, in pounds                                                                                                          | 6          | 6          |
| 341y | Length or 1st Dimension, in inches                                                                                                  | 6          | 6          |
| 342y | Length or 1st Dimension, in feet                                                                                                    | 6          | 6          |
| 343y | Container Length/1st Dimension in, in yards                                                                                         | 6          | 6          |
| 344y | Width, Diameter, or 2nd Dimension, in inches                                                                                        | 6          | 6          |
| 345y | Width, Diameter, or 2nd Dimension, in feet                                                                                          | 6          | 6          |
| 346y | Width, Diameter, or 2nd Dimension, in yards                                                                                         | 6          | 6          |
| 347y | Depth, Thickness, Height, or 3rd Dimension, in inches                                                                               | 6          | 6          |
| 348y | Depth, Thickness, Height, or 3rd Dimension, in feet                                                                                 | 6          | 6          |
| 349y | Depth, Thickness, Height, 3rd Dimension, in yards                                                                                   | 6          | 6          |
| 350y | Area, in square inches (variable measure trade item)                                                                                | 6          | 6          |
| 351y | Area, in square feet (variable measure trade item)                                                                                  | 6          | 6          |
| 352y | Area, in square yards (variable measure trade item)                                                                                 | 6          | 6          |
| 353y | Area, in square inches                                                                                                              | 6          | 6          |
| 354y | Area, in square feet                                                                                                                | 6          | 6          |
| 355y | Area, in square yards                                                                                                               | 6          | 6          |
| 356y | Net Weight, in troy ounces (variable measure trade item)                                                                            | 6          | 6          |
| 357y | Net Weight or volume, in ounces (variable measure trade item)                                                                       | 6          | 6          |
| 360y | Net Volume, in quarts (variable measure trade item)                                                                                 | 6          | 6          |
| 361y | Net Volume, in U.S. gallons (variable measure trade item)                                                                           | 6          | 6          |
| 362y | Logistic Volume, in quarts                                                                                                          | 6          | 6          |
| 363y | Logistic Volume, in U.S. gallons                                                                                                    | 6          | 6          |
| 364y | Net Volume, in cubic inches (variable measure trade item)                                                                           | 6          | 6          |
| 365y | Net Volume, in cubic feet (variable measure trade item)                                                                             | 6          | 6          |
| 366y | Net Volume, in cubic yards (variable measure trade item)                                                                            | 6          | 6          |
| 367y | Logistic Volume, in cubic inches                                                                                                    | 6          | 6          |
| 368y | Logistic Volume, in cubic feet                                                                                                      | 6          | 6          |
| 369y | Logistic Volume, in cubic yards                                                                                                     | 6          | 6          |
| 37   | Count of trade items                                                                                                                | 1          | 8          |
| 390y | Applicable Amount Payable or Coupon Value, in local currency                                                                        | 1          | 15         |
| 391y | Applicable Amount Payable with ISO Currency Code                                                                                    | 4          | 18         |
| 392y | Applicable Amount Payable, Single Monetary Area (variable measure trade item)                                                       | 1          | 15         |
| 393y | Applicable Amount Payable With ISO Currency Code (variable measure trade item)                                                      | 4          | 18         |
| 394y | Percentage Discount of a Coupon                                                                                                     | 4          | 4          |
| 395y | Amount Payable per unit of measure single monetary area (variable measure trade item)                                               | 6          | 6          |
| 400  | Customer's Purchase Order Number                                                                                                    | 1          | 30         |
| 401  | Global Identification Number for Consignment (GINC)                                                                                 | 1          | 30         |
| 402  | Global Shipment Identification Number (GSIN)                                                                                        | 17         | 17         |
| 403  | Routing Code                                                                                                                        | 1          | 30         |
| 410  | Ship To/Deliver To Global Location Number                                                                                           | 13         | 13         |
| 411  | Bill To/Invoice To Global Location Number                                                                                           | 13         | 13         |
| 412  | Purchased From Global Location Number                                                                                               | 13         | 13         |
| 413  | Ship For/Deliver For/Forward To Global Location Number                                                                              | 13         | 13         |
| 414  | Identification of a Physical Location - Global Location Number                                                                      | 13         | 13         |
| 415  | Global Location Number of The Invoicing Party                                                                                       | 13         | 13         |
| 416  | Global Location Number of The Production or Service Location                                                                        | 13         | 13         |
| 417  | Party GLN                                                                                                                           | 13         | 13         |
| 420  | Ship To/Deliver To Postal Code Within a Single Postal Authority                                                                     | 1          | 20         |
| 421  | Ship To/Deliver To Postal Code With ISO Country Code                                                                                | 4          | 12         |
| 422  | Country of Origin of a Trade Item                                                                                                   | 3          | 3          |
| 423  | Country of Initial Processing                                                                                                       | 3          | 15         |
| 424  | Country of Processing                                                                                                               | 3          | 3          |
| 425  | Country of Disassembly                                                                                                              | 3          | 3          |
| 426  | Country Covering Full Process Chain                                                                                                 | 3          | 3          |
| 427  | Country Subdivision of Origin                                                                                                       | 1          | 3          |
| 4300 | Ship-to / Deliver-to company name                                                                                                   | 1          | 35         |
| 4301 | Ship-to / Deliver-to contact                                                                                                        | 1          | 35         |
| 4302 | Ship-to / Deliver-to address line 1                                                                                                 | 1          | 70         |
| 4303 | Ship-to / Deliver-to address line 2                                                                                                 | 1          | 70         |
| 4304 | Ship-to / Deliver-to suburb                                                                                                         | 1          | 70         |
| 4305 | Ship-to / Deliver-to locality                                                                                                       | 1          | 70         |
| 4306 | Ship-to / Deliver-to region                                                                                                         | 1          | 70         |
| 4307 | Ship-to / Deliver-to country code                                                                                                   | 2          | 2          |
| 4308 | Ship-to / Deliver-to telephone number                                                                                               | 1          | 30         |
| 4310 | Return-to company name                                                                                                              | 1          | 35         |
| 4311 | Return-to contact                                                                                                                   | 1          | 35         |
| 4312 | Return-to address line 1                                                                                                            | 1          | 70         |
| 4313 | Return-to address line 2                                                                                                            | 1          | 70         |
| 4314 | Return-to suburb                                                                                                                    | 1          | 70         |
| 4315 | Return-to locality                                                                                                                  | 1          | 70         |
| 4316 | Return-to region                                                                                                                    | 1          | 70         |
| 4317 | Return-to country code                                                                                                              | 2          | 2          |
| 4318 | Return-to postal code                                                                                                               | 1          | 20         |
| 4319 | Return-to telephone number                                                                                                          | 1          | 30         |
| 4320 | Service code description                                                                                                            | 1          | 35         |
| 4321 | Dangerous goods flag                                                                                                                | 1          | 1          |
| 4322 | Authority to leave                                                                                                                  | 1          | 1          |
| 4323 | Signature required flag                                                                                                             | 1          | 1          |
| 4324 | Not before delivery date time                                                                                                       | 10         | 10         |
| 4325 | Not after delivery date time                                                                                                        | 10         | 10         |
| 4326 | Release date                                                                                                                        | 6          | 6          |
| 7001 | NATO Stock Number (NSN)                                                                                                             | 13         | 13         |
| 7002 | UN/ECE Meat Carcasses and Cuts Classification                                                                                       | 1          | 30         |
| 7003 | Expiration Date and Time                                                                                                            | 10         | 10         |
| 7004 | Active Potency                                                                                                                      | 1          | 4          |
| 7005 | Catch Area                                                                                                                          | 1          | 12         |
| 7006 | First Freeze Date                                                                                                                   | 6          | 6          |
| 7007 | Harvest Date                                                                                                                        | 6          | 12         |
| 7008 | Species For Fishery Purposes                                                                                                        | 1          | 3          |
| 7009 | Fishing Gear Type                                                                                                                   | 1          | 10         |
| 7010 | Production Method                                                                                                                   | 1          | 2          |
| 7020 | Refurbishment Lot ID                                                                                                                | 1          | 20         |
| 7021 | Functional Status                                                                                                                   | 1          | 20         |
| 7022 | Revision Status                                                                                                                     | 1          | 20         |
| 7023 | Global Individual Asset Identifier (GIAI) of an Assembly                                                                            | 1          | 30         |
| 703y | Number of Processor with ISO Country Code                                                                                           | 3          | 30         |
| 7040 | GS1 UIC with Extension 1 and Importer index                                                                                         | 4          | 4          |
| 710  | National Healthcare Reimbursement Number (NHRN) - Germany PZN                                                                       | 1          | 20         |
| 711  | National Healthcare Reimbursement Number (NHRN) - France CIP                                                                        | 1          | 20         |
| 712  | National Healthcare Reimbursement Number (NHRN) - Spain CN                                                                          | 1          | 20         |
| 713  | National Healthcare Reimbursement Number (NHRN) - Brasil DRN                                                                        | 1          | 20         |
| 714  | National Healthcare Reimbursement Number (NHRN) - Portugal AIM                                                                      | 1          | 20         |
| 723y | Certification reference                                                                                                             | 2          | 30         |
| 7240 | Protocol ID                                                                                                                         | 1          | 20         |
| 8001 | Roll Products - Width/Length/Core Diameter/Direction/Splices                                                                        | 14         | 14         |
| 8002 | Cellular Mobile Telphone Identifier                                                                                                 | 1          | 20         |
| 8003 | Global Returnable Asset Identifier (GRAI)                                                                                           | 15         | 30         |
| 8004 | Global Individual Asset Identifier (GIAI)                                                                                           | 1          | 30         |
| 8005 | Price per Unit of Measure                                                                                                           | 6          | 6          |
| 8006 | Identification of an Individual Trade Item Piece                                                                                    | 18         | 18         |
| 8007 | International Bank Account Number (IBAN)                                                                                            | 1          | 34         |
| 8008 | Date and Time of Production                                                                                                         | 8          | 12         |
| 8009 | Optically Readable Sensor Indicator                                                                                                 | 1          | 50         |
| 8010 | Component/Part Identifier (CPID)                                                                                                    | 1          | 30         |
| 8011 | Component/Part Identifier Serial Number (CPID Serial)                                                                               | 1          | 12         |
| 8012 | Software Version                                                                                                                    | 1          | 20         |
| 8013 | Global Model Number (GMN)                                                                                                           | 1          | 30         |
| 8017 | Global Service Relation Number to Identify the Relationship Between an Organisation Offering Services and the Provider of Services  | 18         | 18         |
| 8018 | Global Service Relation Number to Identify the Relationship Between an Organisation Offering Services and the Recipient of Services | 18         | 18         |
| 8019 | Service Relation Instance Number (SRIN)                                                                                             | 1          | 10         |
| 8020 | Payment Slip Reference Number                                                                                                       | 1          | 25         |
| 8026 | Identification of pieces of a trade item (ITIP) contained in a logistic unit                                                        | 18         | 18         |
| 8110 | Coupon Code Identification for Use in North America                                                                                 | 1          | 70         |
| 8111 | Loyalty Points of a Coupon                                                                                                          | 4          | 4          |
| 8112 | Paperless Coupon Code Identification for Use in North America (AI 8112)                                                             | 1          | 70         |
| 8200 | Extended Packaging URL                                                                                                              | 1          | 70         |
| 90   | Information Mutually Agreed Between Trading Partners                                                                                | 1          | 30         |
| 91   | Internal Company Codes                                                                                                              | 1          | 90         |
| 92   | Internal Company Codes                                                                                                              | 1          | 90         |
| 93   | Internal Company Codes                                                                                                              | 1          | 90         |
| 94   | Internal Company Codes                                                                                                              | 1          | 90         |
| 95   | Internal Company Codes                                                                                                              | 1          | 90         |
| 96   | Internal Company Codes                                                                                                              | 1          | 90         |
| 97   | Internal Company Codes                                                                                                              | 1          | 90         |
| 98   | Internal Company Codes                                                                                                              | 1          | 90         |
| 99   | Internal Company Codes                                                                                                              | 1          | 90         |
