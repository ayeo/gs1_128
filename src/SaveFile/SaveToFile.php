<?php

namespace Ayeo\Barcode\SaveFile;

use Ayeo\Barcode\Printer;

class SaveToFile
{
    /**
     * @var Printer
     */
    private $printer;

    /**
     * @param Printer $printer
     */
    public function __construct($printer)
    {
        $this->printer = $printer;
    }

    /**
     * @param string $text
     * @param string $filename
     * @param bool $withLabel
     * @return void
     */
    public function output($text, $filename, $withLabel)
    {
        imagepng($this->printer->getResource($text, $withLabel), $filename);
    }
}
