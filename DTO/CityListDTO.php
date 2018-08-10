<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\City;

class CityListDTO
{
    /**
     * @var CityDTO[]
     */
    private $cities = [];

    /**
     * @param City[] $cities
     *
     * @return CityListDTO
     */
    public function fillFromArray(array $cities)
    {
        /** @var City $city */
        foreach ($cities as $city) {
            $this->cities[] = (new CityDTO())->fillFromEntity($city);
        }

        return $this;
    }

    /**
     * @return CityDTO[]
     */
    public function getCities()
    {
        return $this->cities;
    }
}
