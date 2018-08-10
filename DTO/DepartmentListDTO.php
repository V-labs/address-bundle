<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Department;

class DepartmentListDTO
{
    /**
     * @var DepartmentDTO[]
     */
    private $departments = [];

    /**
     * @param Department[] $departments
     *
     * @return DepartmentListDTO
     */
    public function fillFromArray(array $departments)
    {
        /** @var Department $department */
        foreach ($departments as $department) {
            $this->departments[] = (new DepartmentDTO())->fillFromEntity($department);
        }

        return $this;
    }

    /**
     * @return DepartmentDTO[]
     */
    public function getDepartments()
    {
        return $this->departments;
    }
}
