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

    public function output($text, $filename, $saveFile, $disposition = 'inline')
    {
        if ($saveFile === true) {
            imagepng($this->printer->getResource($text), $filename);
        } else {
            header(sprintf('Content-Type: %s', $this->getType()));
            header(sprintf('Content-Disposition: %s;filename=%s', $disposition, $filename));
            imagepng($this->printer->getResource($text));
        }
    }
}
