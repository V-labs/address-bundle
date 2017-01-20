<?php

namespace Vlabs\AddressBundle\VO;

class CoordinatesVO
{
    public $latitude;
    public $longitude;

    /**
     * CoordinatesVO constructor.
     * @param string $lat
     * @param string $lon
     */
    public function __construct($lat, $lon)
    {
        if(empty($lat)){
            throw new \InvalidArgumentException('Please provide a latitude.');
        }

        if(empty($lon)){
            throw new \InvalidArgumentException('Please provide a longitude.');
        }

        $this->latitude     = $lat;
        $this->longitude    = $lon;
    }
}