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
        //$this->assertEquals($binary, $this->getBuilder()->build($value));
    }

    //compared with http://example.barcodephp.com/html/BCGgs1128.php
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
            ],
            [
                '12111111', //(12)111111
                '110100111001111010111010110011100110001001001100010010011000100100111010110001100011101011'
            ]
        ];
    }

    /**
     * @dataProvider realDataProvider
     */
    public function testReal($value, $expected)
    {
        $this->assertEquals($expected, $this->getBuilder()->generate($value));
    }

    public function realDataProvider()
    {
        return [
            [
                '(12)111111',
                '110100111001111010111010110011100110001001001100010010011000100100111010110001100011101011'
            ],
            [
                '(00)159052793120000019',
                '110100111001111010111011011001100101110011001101111011011011100010100011110101101100011011001001110110110011001101100110011001011100110000101001100011101011'
            ],
            [
                '(10)012345',
                '110100111001111010111011001000100110011011001110110111010111011000110001101101100011101011'
            ],
            [
                '(10)12345',
                '11010011100111101011101100100010010110011100100010110001110101111011011100100110110011001100011101011'
            ]

        ];
    }
}