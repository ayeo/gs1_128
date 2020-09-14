<?php
namespace Ayeo\Barcode\Barcode;

use Ayeo\Barcode\Data\BinaryMap;
use Ayeo\Barcode\Data\Subsets;
use Ayeo\Barcode\Model\Section;
use Ayeo\Barcode\Utils;

// https://pl.wikipedia.org/wiki/Kod_128
// gs1-128 requires additional FNC1 chars (the only difference)
// http://www.logisticlabel.com/pl/1/o_gs1.html
class Gs1_128
{
    /**
     * @var Utils\SectionSlicer
     */
    private $slicer;

    private $mapInUse = 'C'; //C generates the shortest code

    /**
     * @var Subsets
     */
    private $subsetsMaps;

    /**
     * @var BinaryMap
     */
    private $binaryMap;

    //fixme: move to Subset class
    private $subsets = [
        'A' => 101,
        'B' => 100,
        'C' =>  99,
    ];

    //fixme: get rid of it
    private $binaryCodeOffsets = [];


    public function __construct()
    {
        $this->slicer = new Utils\SectionSlicer();
        $this->subsetsMaps = new Subsets();
        $this->binaryMap = new BinaryMap();
    }

    public function generate($barcodeString)
    {
        $this->binaryCodeOffsets = [];
        $this->binaryCodeOffsets[] = 105; //start
        $this->binaryCodeOffsets[] = 102; //fcn1

        $sections = $this->slicer->getSections($barcodeString);
        $totalSectionsNumber = count($sections);
        $i = 1;

        $x = [];
        /* @var $section Section */
        foreach ($sections as $section) {
            $cc = $this->getPairs((string)$section);
            $x[] = $cc;
            $this->doShit($cc, $barcodeString);

            if ($i++ < $totalSectionsNumber && $section->hasFixedLength() === false) {
                $this->binaryCodeOffsets[] = 102; //fcn1
            }
        }

        $this->binaryCodeOffsets[] = $this->generateChecksum($this->binaryCodeOffsets);
        $this->binaryCodeOffsets[] = 'STOP';
        $this->binaryCodeOffsets[] = 'TERMINATE';

        $map = $this->binaryMap;
        $join = join('', array_map(function ($n) use ($map) {
            return $map->get($n);
        }, $this->binaryCodeOffsets));

        return $join;
    }

    /**
     * @param $letter
     * @param $pair
     * @return bool
     */
    private function setProperSubset($letter, $pair)
    {
        if (array_search((string) $pair, $this->getSubsetMap($letter), true)) {
            $this->mapInUse = $letter;
            $this->binaryCodeOffsets[] = $this->subsets[$letter];
            return true;
        }

        return false;
    }

    /**
     * @param $pair
     * @param $fullCode
     */
    private function checkSubsetMap($pair, $fullCode)
    {
        if (array_search((string) $pair, $this->getCurrentSubset(), true)) {
            return;
        }

        //if barcode contains small letters try to use B up front
        if (preg_match('#[a-z]+#', $fullCode)) {
            if ($this->setProperSubset('B', $pair)) {
                return;
            }
        }

        foreach (array_keys($this->subsets) as $letter) {
            if ($this->setProperSubset($letter, $pair)) {
                return;
            }
        }
    }

    /**
     * @param $array
     * @return string (binary)
     */
    private function generateChecksum($array)
    {
        $total = 0;
        foreach ($array as $i => $value) {
            $multiplier = $i === 0 ? 1 : $i;
            $total += $value * $multiplier;
        }

        return $this->binaryMap->getPosition($total % 103);
    }

    /**
     * @param $array
     * @param $fullCode
     */
    private function doShit($array, $fullCode)
    {
        foreach ($array as $pair) {
            $this->checkSubsetMap($pair, $fullCode);
            $subset = $this->getCurrentSubset();
            $key = array_search($pair, $subset, true);
            if ($key) {
                $this->binaryCodeOffsets[] = $key;
            } else {
                $this->doShit(str_split($pair), $fullCode);
            }
        }
    }

    /**
     * @param $code
     * @return array
     */
    private function getPairs($code)
    {
        return str_split($code, 2);
    }

    /**
     * @return array
     */
    private function getCurrentSubset()
    {
        return $this->getSubsetMap($this->mapInUse);
    }

    private function getSubsetMap($letter)
    {
        return $this->subsetsMaps->get($letter);
    }
}
