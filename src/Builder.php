<?php
namespace Ayeo\Barcode;

use Ayeo\Barcode\Model\Rgb;
use Ayeo\Barcode\Response\Png;
use Ayeo\Barcode\SaveFile\SaveToFile;

class Builder
{
    /** @var int */
    private $width = 500;

    /** @var int */
    private $height = 150;

    /** @var string */
    private $fontPath = 'FreeSans.ttf';

    /** @var int */
    private $fontSize = 10;

    /** @var string */
    private $filename = 'barcode.png';

    /** @var string */
    private $imageFormat = 'png';

    /** @var array */
    private $supportedBarcodeTypes = [
        'gs1-128' => '\\Ayeo\\Barcode\\Barcode\\Gs1_128'
    ];

    /** @var array */
    private $imagesFormats = [
        'png' => '\\Ayeo\\Barcode\\Response\\Png',
        'jpeg' => '\\Ayeo\\Barcode\\Response\\Jpeg',
    ];

    /**
     * @var Rgb
     */
    private $paintColor;

    /**
     * @var Rgb
     */
    private $backgroundColor;

    /**
     * @var integer
     */
    private $scale;

    public function __construct()
    {
        $this->paintColor = new Rgb(0, 0, 0);
        $this->backgroundColor = new Rgb(255, 255, 255);
    }

    public static function build()
    {
        return new self;
    }

    protected function getPrinter ()
    {
        $printer = new Printer($this->width, $this->height, $this->fontPath);
        $printer->setBackgroundColor($this->backgroundColor);
        $printer->setPrintColor($this->paintColor);
        $printer->setFontSize($this->fontSize);

        if (is_null($this->scale) === false) {
            $printer->imposeScale($this->scale);
        }

        return $printer;
    }

    /**
     * @param string $text
     * @param bool $withLabel
     * @return void
     */
    public function output($text, $withLabel = true)
    {
        $printer = $this->getPrinter();
        $response = new Png($printer);
        
        return $response->output($text, $this->filename, $withLabel);
    }

    /**
     * @param string $text
     * @param bool $withLabel
     * @return string
     */
    public function getBase64($text, $withLabel = true)
    {
        return $this->getPrinter()->getBase64($text, $withLabel);
    }

    /**
     * @param string $text
     * @param bool $withLabel
     * @return void
     */
    public function saveImage($text, $withLabel = true)
    {
        $printer = $this->getPrinter();
        $saveFile = new SaveToFile($printer);

        return $saveFile->output($text, $this->filename, $withLabel);
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    public function setBarcodeType($type)
    {
        if (array_key_exists($type, $this->supportedBarcodeTypes)) {
            $this->type = $type;

            return $this;
        }

        throw new \LogicException('Unsupported barcode format');
    }

    public function setBackgroundColor($r, $g, $b)
    {
        $this->backgroundColor = new Rgb($r, $g, $b);

        return $this;
    }

    public function setPaintColor($r, $g, $b)
    {
        $this->paintColor = new Rgb($r, $g, $b);

        return $this;
    }

    public function setFontPath($path)
    {
        //todo: file exists;
        $this->fontPath = $path;
    }

    public function setFontSize($size)
    {
        //todo: must be integer
        $this->fontSize = $size;
    }

    public function setImageFormat($format)
    {
        if (array_key_exists($format, $this->imagesFormats))
        {
            $this->imageFormat = $format;

            return $this;
        }

        throw new \LogicException('Unsupported image format');
    }

    public function setFilename($filename)
    {
        //check extension
        $this->filename = $filename;
    }

    public function imposeScale($scale)
    {
        //todo: must be between 1-5, integer
        $this->scale = $scale;
    }

}