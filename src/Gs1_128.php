<?php
namespace Ayeo\Barcode;

// Class using only Subset C to encode data - no jak chuj
// https://pl.wikipedia.org/wiki/Kod_128
// gs1-128 requires additional FNC1 chars (the only difference)
// http://www.logisticlabel.com/pl/1/o_gs1.html
use Ayeo\Barcode\Model\Section;

class Gs1_128
{
    /**
     * @var SectionSlicer
     */
    private $slicer;

    private $mapInUse = 'C'; //C generates the shortest code

    private $mapC = [];

    private $binaryCodeOffsets = [];

    private $barcodeString;

    public function __construct()
    {
        $this->slicer = new SectionSlicer();

        for ($x = 0; $x < 100; $x++)
        {
            $xx = sprintf('%02d', $x);
            $this->mapC[$xx] = $xx;
        }
    }

    private function checkSubsetMap($pair)
    {
        if (array_search((string) $pair, $this->getCodeMap(), true))
        {
            return;
        }

        //if barcode contains small letters try to use B up front
        //fixme: small duplication here
        if (preg_match('#[a-z]+#', $this->barcodeString))
        {
            if (array_search((string) $pair, $this->mapB, true))
            {
                $this->mapInUse = 'B';
                $this->binaryCodeOffsets[] = 100; //shift map to B
                return;
            }
        }

        if (array_search((string) $pair, $this->mapA, true))
        {
            $this->mapInUse = 'A';
            $this->binaryCodeOffsets[] = 101; //shift map to A
            return;
        }

        if (array_search((string) $pair, $this->mapB, true))
        {
            $this->mapInUse = 'B';
            $this->binaryCodeOffsets[] = 100; //shift map to B
            return;
        }

        if (array_search((string) $pair, $this->mapC, true))
        {
            $this->mapInUse = 'C';
            $this->binaryCodeOffsets[] = 99; //shift map to C
            return;
        }
    }

    private function generateChecksum($array)
    {
        $total = 0;
        foreach ($array as $i => $value)
        {
            $multiplier = $i === 0 ? 1 : $i;
            $total += $value * $multiplier;
        }

        return array_keys($this->binaryCodesMap)[$total % 103];
    }

    private function doShit($array)
    {
        foreach ($array as $pair)
        {
            $this->checkSubsetMap($pair);
            $key = array_search($pair, $this->getCodeMap(), true);
            $key === false ? $this->doShit(str_split($pair)) : $this->binaryCodeOffsets[] = $key;
        }
    }

    public function generate($barcodeString)
    {
        $this->barcodeString = $barcodeString;
        $sections = $this->slicer->getSections($barcodeString);
        $this->binaryCodeOffsets = [];
        $this->binaryCodeOffsets[] = 105; //start
        $this->binaryCodeOffsets[] = 102; //fcn1

        /* @var $section Section */
        foreach ($sections as $section)
        {
            $this->doShit($this->getPairs((string) $section));
        }

        $this->binaryCodeOffsets[] = $this->generateChecksum($this->binaryCodeOffsets);
        $this->binaryCodeOffsets[] = 'STOP';
        $this->binaryCodeOffsets[] = 'TERMINATE';

        $map = $this->binaryCodesMap;
        return join('', array_map(function($n) use ($map) {return $map[$n];}, $this->binaryCodeOffsets));
    }

    private function getPairs($code)
    {
        return str_split($code, 2);
    }


    private $mapB = [
        ' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', // 9 (end)
        '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', // 19
        '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', // 29
        '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', // 39
        'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', // 49
        'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', // 59
        '\\', ']', '^', '_', '`', 'a', 'b', 'c', 'd', 'e', // 69
        'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', // 79
        'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', // 89
        'z', '{', '|', '}', '~', "\x7F", // 95

        // Now for system codes
        'FNC_3', 'FNC_2', 'SHIFT_A', 'CODE_C', 'FNC_4', // 100
        'CODE_A', 'FNC_1', 'START_A', 'START_B', 'START_C', // 105
        'STOP',	// 106
    ];

    private $mapA = [
        ' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', // 9 (end)
        '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', // 19
        '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', // 29
        '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', // 39
        'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', // 49
        'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', // 59
        '\\', ']', '^', '_', // 63 (We're going into the weird bytes next)

        // Hex is a little more concise in this context
        "\x00", "\x01", "\x02", "\x03", "\x04", "\x05", // 69
        "\x06", "\x07", "\x08", "\x09", "\x0A", "\x0B", // 75
        "\x0C", "\x0D", "\x0E", "\x0F", "\x10", "\x11", // 81
        "\x12", "\x13", "\x14", "\x15", "\x16", "\x17", // 87
        "\x18", "\x19", "\x1A", "\x1B", "\x1C", "\x1D", // 93
        "\x1E", "\x1F", // 95

        // Now for system codes
        'FNC_3', 'FNC_2', 'SHIFT_B', 'CODE_C', 'CODE_B', // 100
        'FNC_4', 'FNC_1', 'START_A', 'START_B', 'START_C', // 105
        'STOP',	// 106
    ];

    private $binaryCodesMap = [
        "00" => "11011001100",
        "01" => "11001101100",
        "02" => "11001100110",
        "03" => "10010011000",
        "04" => "10010001100",
        "05" => "10001001100",
        "06" => "10011001000",
        "07" => "10011000100",
        "08" => "10001100100",
        "09" => "11001001000",
        "10" => "11001000100",
        "11" => "11000100100",
        "12" => "10110011100",
        "13" => "10011011100",
        "14" => "10011001110",
        "15" => "10111001100",
        "16" => "10011101100",
        "17" => "10011100110",
        "18" => "11001110010",
        "19" => "11001011100",
        "20" => "11001001110",
        "21" => "11011100100",
        "22" => "11001110100",
        "23" => "11101101110",
        "24" => "11101001100",
        "25" => "11100101100",
        "26" => "11100100110",
        "27" => "11101100100",
        "28" => "11100110100",
        "29" => "11100110010",
        "30" => "11011011000",
        "31" => "11011000110",
        "32" => "11000110110",
        "33" => "10100011000",
        "34" => "10001011000",
        "35" => "10001000110",
        "36" => "10110001000",
        "37" => "10001101000",
        "38" => "10001100010",
        "39" => "11010001000",
        "40" => "11000101000",
        "41" => "11000100010",
        "42" => "10110111000",
        "43" => "10110001110",
        "44" => "10001101110",
        "45" => "10111011000",
        "46" => "10111000110",
        "47" => "10001110110",
        "48" => "11101110110",
        "49" => "11010001110",
        "50" => "11000101110",
        "51" => "11011101000",
        "52" => "11011100010",
        "53" => "11011101110",
        "54" => "11101011000",
        "55" => "11101000110",
        "56" => "11100010110",
        "57" => "11101101000",
        "58" => "11101100010",
        "59" => "11100011010",
        "60" => "11101111010",
        "61" => "11001000010",
        "62" => "11110001010",
        "63" => "10100110000",
        "64" => "10100001100",
        "65" => "10010110000",
        "66" => "10010000110",
        "67" => "10000101100",
        "68" => "10000100110",
        "69" => "10110010000",
        "70" => "10110000100",
        "71" => "10011010000",
        "72" => "10011000010",
        "73" => "10000110100",
        "74" => "10000110010",
        "75" => "11000010010",
        "76" => "11001010000",
        "77" => "11110111010",
        "78" => "11000010100",
        "79" => "10001111010",
        "80" => "10100111100",
        "81" => "10010111100",
        "82" => "10010011110",
        "83" => "10111100100",
        "84" => "10011110100",
        "85" => "10011110010",
        "86" => "11110100100",
        "87" => "11110010100",
        "88" => "11110010010",
        "89" => "11011011110",
        "90" => "11011110110",
        "91" => "11110110110",
        "92" => "10101111000",
        "93" => "10100011110",
        "94" => "10001011110",
        "95" => "10111101000",
        "96" => "10111100010",
        "97" => "11110101000",
        "98" => "11110100010",
        "99" => "10111011110",
        "100" => "10111101110",
        "101" => "11101011110",
        "102" => "11110101110",
        "103" => "11010000100",
        "104" => "11010010000",
        "105" => "11010011100",
        "START" => "11010011100",
        "FNC1" => "11110101110",
        "STOP" => "11000111010",
        "TERMINATE" => "11"
    ];

    private function getCodeMap()
    {
        if ($this->mapInUse === 'A')
        {
            return $this->mapA;
        }

        if ($this->mapInUse === 'B')
        {
            return $this->mapB;
        }

        if ($this->mapInUse === 'C')
        {
            return $this->mapC;
        }
    }
}
