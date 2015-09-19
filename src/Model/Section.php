<?php
namespace Ayeo\Barcode\Model;

class Section implements \JsonSerializable
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var strings
     */
    private $value;

    private $fixedLength = false;

    /**
     * @param string $identifier
     * @param string $value
     */
    public function __construct($identifier, $value, $fixedLength = false)
    {
        $this->fixedLength = (bool) $fixedLength;
        $this->identifier = $identifier;
        $this->value = $value;
    }

    public function hasFixedLength()
    {
        return $this->fixedLength;
    }

    function jsonSerialize() //for test purpose only, todo: find better way to achieve this goal
    {
        return [$this->identifier, $this->value];
    }

    public function __toString()
    {
        return sprintf('%s%s', $this->identifier, $this->value);
    }

}