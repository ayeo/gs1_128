<?php
namespace Ayeo\Barcode;

use Ayeo\Barcode\Model\Rgb;
use Ayeo\Barcode\Response\Png;

class Builder
{
    private $width = 500;

    private $height = 150;

    private $fontPath = '/assets/FreeSans.ttf';

    private $fontSize = 10;

    private $filename = 'barcode.png';

    private $imageFormat = 'png';

    private $supportedBarcodeTypes = [
        'gs1-128' => '\\Ayeo\\Barcode\\Barcode\\Gs1_128'
    ];

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

    public function __construct()
    {
        $this->paintColor = new Rgb(0, 0, 0);
        $this->backgroundColor = new Rgb(255, 255, 255);
    }

    public static function build()
    {
        return new self;
    }

    public function output($text)
    {
        $printer = new Printer($this->width, $this->height, $this->fontPath);
        $printer->setBackgroundColor($this->backgroundColor);
        $printer->setPrintColor($this->paintColor);
        $printer->setFontSize($this->fontSize);


        $response = new Png($printer);
        return $response->output($text, $this->filename);


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

    public function setImageFromat($format)
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


}