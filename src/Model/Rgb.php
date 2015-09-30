<?php
namespace Ayeo\Barcode\Model;

class Rgb
{
    public $red;

    public $green;

    public $blue;

    public function __construct($r, $g, $b)
    {
        //todo check if beetwen 0-255!
        $this->red = $r;
        $this->green = $g;
        $this->blue = $b;
    }
}