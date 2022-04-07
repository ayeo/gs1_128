<?php

namespace Ayeo\Barcode\Test;

use Ayeo\Barcode\Printer;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PrinterTest extends TestCase
{
    public function testGettingBarHeightAfterCreatingPrinter()
    {
        $printer = new Printer(100, 100);
        $this->assertEquals(90, $printer->getBarHeight());
        $this->assertEquals(10, $printer->getFontSize());
    }

    public function testBarHeightWithLabel()
    {
        $printer = new Printer(100, 100);
        $printer->getBase64("001", true);
        //fixme: getBase64 should not modify state of the printer
        $this->assertEquals(80, $printer->getBarHeight());
    }

    public function testBarHeightWithoutLabel()
    {
        $printer = new Printer(100, 100);
        $printer->getBase64("001", false);
        $this->assertEquals(90, $printer->getBarHeight());
    }

    public function testIfInvalidFontSizeDontChangeBarHeight()
    {
        try {
            $printer = new Printer(100, 25);
            $printer->setFontSize(20);
        } catch (RuntimeException $e) {
            $this->assertEquals(15, $printer->getBarHeight());
            $this->assertEquals(10, $printer->getFontSize());
        }
    }

    public function testTooSmallHeight()
    {
        $this->setExpectedException("\RuntimeException", "Image is too short");
        new Printer(100, 15);
    }
}
