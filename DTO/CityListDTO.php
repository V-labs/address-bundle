<?php

namespace Vlabs\AddressBundle\DTO;

class CityListDTO
{
    /**
     * @var array
     */
    public $cities = [];

    /**
     * @param array $cities
     * @return $this
     */
    public function fillFromArray(array $cities)
    {
        foreach ($cities as $city) {
            $this->cities[] = (new CityDTO())->fillFromEntity($city);
        }

        return $this;
    }
}
