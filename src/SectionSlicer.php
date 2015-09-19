<?php
namespace Ayeo\Barcode;

use Ayeo\Barcode\Model\Section;

class SectionSlicer
{
    /**
     * @var SectionBuilder
     */
    private $sectionBuilder;

    public function __construct()
    {
        $this->sectionBuilder = new SectionBuilder();
    }

    public function getSections($data)
    {
        $pattern = '#(\d+?)\d+#';
        preg_match_all($pattern, $data, $matches);

        $expected = [];
        foreach (array_chunk($matches[0], 2) as $sectionData)
        {
            $expected[] = $this->sectionBuilder->build($sectionData[0], $sectionData[1]);
        }

        return $expected;
    }
}