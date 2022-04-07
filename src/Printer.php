<?php
namespace Ayeo\Barcode;

use Ayeo\Barcode\Barcode\Gs1_128;
use Ayeo\Barcode\Model\Rgb;
use Ayeo\Barcode\Utils\ScaleCalculator;
use RuntimeException;

/**
 * Class is responsible for generating barcode image
 */
class Printer
{
    /**
     * Barcode width in pixels
     *
     * @var int
     */
    private $width;

    /**
     * Barcode height in pixels
     *
     * @var int
     */
    private $height;

    /**
     * Font size in pixels
     *
     * @var integer
     */
    private $fontSize;

    /**
     * Path to ttf file
     *
     * @var string
     */
    private $fontPath;

    /**
     * @var Rgb
     */
    private $backgroundColor;

    /**
     * @var Rgb
     */
    private $printColor;

    /**
     * @var Gs1_128
     */
    private $barsGenerator;

    /**
     * @var resource an image resource identifier
     */
    private $imageHandler;

    /**
     * @var integer
     */
    private $allocatedBackgroundColor;

    /**
     * @var integer
     */
    private $allocatedPrintColor;

    /**
     * Scale is calculated based on image width and code length
     *
     * @var integer 1-5
     */
    private $scale;

    /**
     * Bar height depends on image height and text font size
     *
     * @var integer
     */
    private $barHeight;

    private $scaleCalculator;

    private $imposedScale;

    /**
     * @param integer $width
     * @param integer $height
     * @param null|string $fontPath
     */
    public function __construct($width, $height, $fontPath = 'FreeSans.ttf')
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

    public function imposeScale($scale)
    {
        $this->imposedScale = $scale;
    }

    /**
     * @param string $textToEncode
     * @param bool $withLabel
     * @throws RuntimeException
     * @return void
     */
    private function prepare($textToEncode, $withLabel)
    {
        $textToEncode = preg_replace('/\s+/', '', $textToEncode);
        $this->imageHandler = imagecreate($this->width, $this->height);
        $this->allocatedBackgroundColor = $this->initColor($this->backgroundColor);
        $this->allocatedPrintColor = $this->initColor($this->printColor);
        $this->handleBarHeight($withLabel);
        $barcode = $this->barsGenerator->generate($textToEncode);

        if ($this->imposedScale) {
            $this->scale = $this->imposedScale;
        } else {
            $this->scale = $this->scaleCalculator->getBarWidth($this->width, $barcode);
        }

        $this->printBars($barcode);

        if ($withLabel) {
            $this->printText($textToEncode);
        }
    }

    /**
     * @param $textToEncode
     * @param bool $wihtLabel
     * @return string
     */
    public function getBase64($textToEncode, $withLabel = true)
    {
        $this->prepare($textToEncode, $withLabel);

        ob_start ();
        imagepng($this->imageHandler);
        $content = ob_get_contents();
        ob_end_clean ();

        return base64_encode($content);
    }

    /**
     * @param $textToEncode
     * @param bool $wihtLabel
     * @return resource
     */
    public function getResource($textToEncode, $withLabel)
    {
        $this->prepare($textToEncode, $withLabel);

        return $this->imageHandler;
    }

    /**
     * @param integer $fontSize
     */
    public function setFontSize($fontSize)
    {
        //todo: must be greater than 0
        $this->fontSize = $fontSize;
        $this->handleBarHeight(false);
    }

    public function getBarHeight()
    {
        return $this->barHeight;
    }

    public function getFontSize()
    {
        return $this->fontSize;
    }

    private function handleBarHeight($withLabel)
    {
        $barHeight = $this->height - 10;
        if ($withLabel) {
            $barHeight -= $this->fontSize;
        }

        if ($barHeight < 10) {
            throw new RuntimeException('Image is too short');
        }

        $this->barHeight = $barHeight;
    }

    /**
     * @param Rgb $color
     */
    public function setBackgroundColor(Rgb $color)
    {
        $this->backgroundColor = $color;
    }

    /**
     * @param Rgb $color
     */
    public function setPrintColor(Rgb $color)
    {
        $this->printColor = $color;
    }

    /**
     *
     * @param Rgb $color
     * @return int color identifier
     */
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

    /**
     * @param $barcode string binary format: 001101011101
     */
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

    /**
     * @param $text
     * @throws RuntimeException
     */
    private function printText($text)
    {
        if (function_exists('imagettftext') === false) {
            throw new RuntimeException('This method requires PHP with FreeType extension installed');
        }

        //2.68 for 10
        //2.80 for 9
        $x = __DIR__."/".$this->fontPath;
        $xPosition = abs(($this->width / 2)) - strlen($text)* $this->fontSize / 2.8;
        imagettftext(
            $this->imageHandler,
            $this->fontSize,
            $angle = 0,
            $xPosition,
            $y = $this->barHeight + 10 + $this->fontSize - 5,
            $this->allocatedPrintColor,
            $x,
            $text
        );
    }
}
