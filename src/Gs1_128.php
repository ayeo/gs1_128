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

    private $mapInUse = 'C';

    public function __construct()
    {
        $this->slicer = new SectionSlicer();
    }
    //temporary dev method
    public function generate($barcodeString)
    {
        $checkSum = [];
        $sections = $this->slicer->getSections($barcodeString);


        $barcode = [];
        $barcode[] = $this->getBinary("START"); //fixme: use single quote
        $barcode[] = $this->getBinary("FNC1");

        $checkSum[] = 105; //START C
        $checkSum[] = 102; //FCN1


        $i = 2;
        /* @var $section Section */
        foreach ($sections as $section)
        {
            foreach ($this->getPairs((string) $section) as $pair)
            {
                if ($pair == 5)
                {
                    $barcode[] = "11101011110";
                    $checkSum[] = 101 * $i;
                    $i++;
                    $this->mapInUse = 'A';
                }

                $barcode[] = $this->getBinary($pair);

                $map = $this->getCodeMap();
                if ($this->mapInUse == 'A')
                {
                    $xxx = array_search($pair, $map);
                }
                else
                {
                    $xxx = $pair;
                }

                $checkSum[] = $xxx * $i;
                $i++;

            }
        }


        //fixme
        $xx = '';
        foreach ($sections as $section)
        {
            $xx .= (string) $section;
        }

        $key = array_sum($checkSum) % 103;
        $code_keys = array_keys($this->mapC);
        $xxx =  $this->mapC[$code_keys[$key]];

        $barcode[] = $xxx;
        $this->mapInUse = 'C';
        $barcode[] = $this->getBinary("STOP");
        $barcode[] = $this->getBinary("TERMINATE");

        return join('', $barcode);
    }



    private function getBinary($offset)
    {
        //check if exists
        $code128c_codes = $this->getCodeMap();

        if ($this->mapInUse === 'A')
        {
            $key = array_search($offset, $code128c_codes);
            return $this->mapC[$key];
        }
        else
        {
            return $code128c_codes[$offset];
        }


    }

    private function getPairs($code)
    {
        return str_split($code, 2); //test
    }




    private $mapA = array(
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
    );

    private $mapC =  [
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
            "TERMINATE" => "11",
            "START_DATA" => "105",
            "FNC1_DATA" => "102" //wtf
    ];

    private function getCodeMap()
    {
        if ($this->mapInUse === 'C')
        {
            return $this->mapC;
        }

        if ($this->mapInUse === 'A')
        {
            return $this->mapA;
        }
    }
}
