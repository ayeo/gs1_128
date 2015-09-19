<?php
namespace Ayeo\Barcode\Test;

use Ayeo\Barcode\Model\Section;
use Ayeo\Barcode\SectionSlicer;

class SectionSlicerTest extends \PHPUnit_Framework_TestCase
{
    public function testSlicing()
    {
        $slicer = new SectionSlicer();
        $sections = $slicer->getSections('(00)123451234512345123(10)42432423');

        $expected = [
            new Section('00', '123451234512345123'),
            new Section('10', '42432423')
        ];

        $this->assertEquals(json_encode ($expected), json_encode($sections));
    }
}