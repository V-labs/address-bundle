<?php

namespace Vlabs\AddressBundle\DTO;

class DepartmentListDTO
{
    /**
     * @var array
     */
    public $departments = [];

    /**
     * @param array $departments
     * @return $this
     */
    public function fillFromArray(array $departments)
    {
        foreach ($departments as $department) {
            $this->departments[] = (new DepartmentDTO())->fillFromEntity($department);
        }

        return $this;
    }
}
