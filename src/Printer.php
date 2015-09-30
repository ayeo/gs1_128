<?php
namespace Ayeo\Barcode;

use Ayeo\Barcode\Barcode\Gs1_128;
use Ayeo\Barcode\Model\Rgb;
use Ayeo\Barcode\Utils\ScaleCalculator;

class Printer
{
    private $width;

    private $height;

    private $scaleCalculator;

    private $fontSize;

    private $barHeight;

    private $fontPath;

    /**
     * @var Rgb
     */
    private $backgroundColor;

    /**
     * @var Rgb
     */
    private $printColor;

    private $barsGenerator;

    /**
     * @var resource an image resource identifier
     */
    private $imageHandler;

    private $allocatedBackgroundColor;

    private $allocatedPrintColor;

    /**
     * @var integer 1-5
     */
    private $scale;

    public function __construct($width, $height, $fontPath = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->fontPath = $fontPath;

        $this->scaleCalculator = new ScaleCalculator();
        $this->barsGenerator = new Gs1_128();

        $this->setFontSize(10);
        $this->setBackgroundColor(new Rgb(255, 255, 255));
        $this->setPrintColor(new Rgb(0, 0, 0));
    }

    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
        $this->barHeight = $this->height - $this->fontSize - 10;

        if ($this->barHeight < 10)
        {
            throw new \RuntimeException('Image is to short');
        }

        return $this;
    }

    public function setBackgroundColor(Rgb $color)
    {
        $this->backgroundColor = $color;

        return $this;
    }

    public function setPrintColor(Rgb $color)
    {
        $this->printColor = $color;

        return $this;
    }

    private function initColor(Rgb $color)
    {
        $result = imagecolorallocate(
            $this->imageHandler,
            $color->red,
            $color->green,
            $color->blue
        );

        return $result;
    }

    private function printBars($barcode)
    {
        $barcodeLength = strlen($barcode) * $this->scale;
        $xPosition = abs(($this->width - $barcodeLength) / 2);

        for ($i=0; $i < strlen($barcode); $i++) {
            $val = strtolower($barcode[$i]);

            if ($val == "1") {
                imagefilledrectangle(
                    $this->imageHandler,
                    $xPosition,
                    10,
                    $xPosition + $this->scale - 1,
                    $this->barHeight,
                    $this->allocatedPrintColor
                );
            }

            $xPosition += $this->scale;
        }
    }

    private function printText($text)
    {
        $xPosition = abs(($this->width / 2)) - strlen($text)* $this->fontSize / 2.68;
        imagettftext(
            $this->imageHandler,
            $this->fontSize,
            $angle = 0,
            $xPosition,
            $y = $this->barHeight + 10 + $this->fontSize - 5,
            $this->allocatedPrintColor,
            $this->fontPath,
            $text
        );
    }

    public function getResource($textToEncode)
    {
        $this->imageHandler = imagecreate($this->width, $this->height);
        $this->allocatedBackgroundColor = $this->initColor($this->backgroundColor);
        $this->allocatedPrintColor = $this->initColor($this->printColor);

        $barcode = $this->barsGenerator->generate($textToEncode);
        $this->scale = $this->scaleCalculator->getBarWidth($this->width, $barcode);

        $this->printBars($barcode);
        $this->printText($textToEncode);

        return $this->imageHandler;
    }


}
