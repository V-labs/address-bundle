<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Region;

class RegionListDTO
{
    /**
     * @var RegionDTO[]
     */
    private $regions = [];

    /**
     * @param Region[] $regions
     *
     * @return RegionListDTO
     */
    public function fillFromArray(array $regions)
    {
        /** @var Region $region */
        foreach ($regions as $region) {
            $this->regions[] = (new RegionDTO())->fillFromEntity($region);
        }

        return $this;
    }

    /**
     * @return RegionDTO[]
     */
    public function getRegions()
    {
        return $this->regions;
    }
}
