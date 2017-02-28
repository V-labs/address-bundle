<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Region;

class RegionDTO
{
    public $id;
    public $name;

    /**
     * @param Region $region
     * @return $this
     */
    public function fillFromEntity(Region $region)
    {
        $this->id = $region->getId();
        $this->name = $region->getName();

        return $this;
    }
}
