<?php
namespace Ayeo\Barcode\SaveFile;

use Ayeo\Barcode\Printer;

class SaveToFile
{
    public function __construct(Printer $printer)
    {
        $this->printer = $printer;
    }

    public function output($text, $filename)
    {
        imagepng($this->printer->getResource($text), $filename);
    }
}
