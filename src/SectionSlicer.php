<?php
namespace Ayeo\Barcode;

use Ayeo\Barcode\Model\Section;

class SectionSlicer
{
    /**
     * @var SectionBuilder
     */
    private $sectionBuilder;

    private $klops;

    public function __construct()
    {
        $this->sectionBuilder = new SectionBuilder();
    }

    public function getSections($data)
    {
        $this->klops = [];

        $pattern = '#\((\d+)\)(.+)#';
        preg_match_all($pattern, $data, $matches);

        $zupa = [];
        for ($x = 0; $x < count($matches[0]); $x++)
        {
            $zupa[] = $matches[1][$x];
            $zupa[] = $matches[2][$x];
        }

        $expected = [];
        foreach (array_chunk($zupa, 2) as $sectionData)
        {
            $expected[] = $this->sectionBuilder->build($sectionData[0], $sectionData[1]);
        }

        return $expected;
    }
}