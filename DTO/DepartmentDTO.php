<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Department;

class DepartmentDTO
{
    public $id;
    public $name;
    public $code;
    public $region;

    /**
     * @param Department $department
     * @return $this
     */
    public function fillFromEntity(Department $department)
    {
        $this->id = $department->getId();
        $this->name = $department->getName();
        $this->code = $department->getCode();
        $this->region = (new RegionDTO())->fillFromEntity($department->getRegion());

        return $this;
    }
}
