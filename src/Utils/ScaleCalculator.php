<?php
namespace Ayeo\Barcode\Utils;

class ScaleCalculator
{
    private $min = 1;

    public function __construct($max = 5)
    {
        $this->max = $max;
    }

    public function getBarWidth($width, $bars)
    {
        $totalBarsNumber = strlen($bars);
        $width = floor($width / $totalBarsNumber);

        if ($width == 0)
        {
            throw new \RuntimeException('Barcode is to long for given width');
        }

        if ($width > $this->max)
        {
            return $this->max;
        }

        if ($width < $this->min)
        {
            return $this->min;
        }

        return $width;
    }
}