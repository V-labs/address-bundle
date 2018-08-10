<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Department;

class DepartmentDTO
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var RegionDTO
     */
    private $region;

    /**
     * @param Department $department
     *
     * @return DepartmentDTO
     */
    public function fillFromEntity(Department $department)
    {
        $this->id       = $department->getId();
        $this->name     = $department->getName();
        $this->code     = $department->getCode();
        $this->region   = (new RegionDTO())->fillFromEntity($department->getRegion());

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return RegionDTO
     */
    public function getRegion()
    {
        return $this->region;
    }
}
