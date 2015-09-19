<?php
namespace Ayeo\Barcode;

use Ayeo\Barcode\Model\Section;

class SectionBuilder
{
    public function build($identifier, $value)
    {
        if (array_key_exists($identifier, $this->data) === false)
        {
            throw new \LogicException(sprintf('Unknown application identifier %s', $identifier));
        }

        //fixme: the logic below belongs to Section domain

        $sectionData = $this->data[$identifier];

        if (strlen($value) < $sectionData[0])
        {
            throw new \LogicException($sectionData[2]);
        }

        if (strlen($value) > $sectionData[1])
        {
            throw new \LogicException($sectionData[2]);
        }

        if ($sectionData[0] === $sectionData[1])
        {
            $fixedLength = true;
        }
        else
        {
            $fixedLength = false;
        }

        //todo: fix length to be even

        return new Section($identifier, $value, $fixedLength);

    }

    private $data = [
        '00' => [18, 18, 'SERIAL SHIPPING CONTAINER CODE (00) - must contains exact 18 digits'],
        '01' => [14, 14, 'GLOBAL TRADE ITEM NUMBER (01) - must contains excat 14 digits'],
        '02' => [14, 14, 'ITEM TRADE ITEM NUMBER (02) - must contains excat 14 digits'],
        '10' => [1, 20, 'BATCH NUMBER (10) - must contains between 1-20 digits'],
        '010' => [1, 20, 'BATCH NUMBER (10) - must contains between 1-20 digits'],

        '12' => [6, 6, 'PAYMENT DATE (12) - must be in YYMMDD format']
    ];
}