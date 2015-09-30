<?php
namespace Ayeo\Barcode\Response;

class Jpeg extends Response
{
    public function getType()
    {
        return 'image/jpeg';
    }
}