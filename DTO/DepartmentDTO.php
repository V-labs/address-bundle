<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Department;

class DepartmentDTO
{
    public $id;
    public $name;
    public $code;

    /**
     * @param Department $department
     * @return $this
     */
    public function fillFromEntity(Department $department)
    {
        $this->id = $department->getId();
        $this->name = $department->getName();
        $this->code = $department->getCode();

        return $this;
    }
}
