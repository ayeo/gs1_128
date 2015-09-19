<?php
namespace Ayeo\Barcode\Test;

use Ayeo\Barcode\Gs1_128;

class Gs1_128Test extends \PHPUnit_Framework_TestCase
{
    private function getBuilder()
    {
        return new Gs1_128();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testBarcodeGenerating($value, $binary)
    {
        $this->assertEquals($binary, $this->getBuilder()->build($value));
    }

    public function dataProvider()
    {
        return [
            [
                '1234',
                '11010011100111101011101011001110010001011000111010011001100011101011'
            ],
            [
                '10',
                '110100111001111010111011001000100110111001001100011101011'
            ],
            [
                '1500100900',
                '11010011100111101011101011100110011011001100110010001001100100100011011001100100110111001100011101011'
            ]
        ];
    }
}