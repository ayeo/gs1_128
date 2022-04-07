<?php

namespace Ayeo\Barcode\Response;

use Ayeo\Barcode\Printer;

abstract class Response
{
    public function __construct(Printer $printer)
    {
        $this->printer = $printer;
    }

    abstract function getType();

    /**
     * @param string $text
     * @param string $filename
     * @param bool $withLabel
     * @param string $disposition
     * @return void
     */
    public function output($text, $filename, $withLabel, $disposition = 'inline')
    {
        header(sprintf('Content-Type: %s', $this->getType()));
        header(sprintf('Content-Disposition: %s;filename=%s', $disposition, $filename));
        imagepng($this->printer->getResource($text, $withLabel));
    }
}
