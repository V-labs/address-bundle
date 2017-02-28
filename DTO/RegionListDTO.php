<?php

namespace Vlabs\AddressBundle\DTO;

class RegionListDTO
{
    /**
     * @var array
     */
    public $regions = [];

    /**
     * @param array $regions
     * @return $this
     */
    public function fillFromArray(array $regions)
    {
        foreach ($regions as $region) {
            $this->regions[] = (new RegionDTO())->fillFromEntity($region);
        }

        return $this;
    }
}
