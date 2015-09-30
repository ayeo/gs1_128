<?php
namespace Ayeo\Barcode\Response;

class Png extends Response
{
    public function getType()
    {
        return 'image/png';
    }
}