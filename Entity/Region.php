<?php

namespace Vlabs\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 */
class Region
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $departments;

    /**
     * @var \Vlabs\AddressBundle\Entity\Country
     */
    private $country;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Region
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add departments
     *
     * @param \Vlabs\AddressBundle\Entity\Department $departments
     * @return Region
     */
    public function addDepartment(\Vlabs\AddressBundle\Entity\Department $departments)
    {
        $this->departments[] = $departments;

        return $this;
    }

    /**
     * Remove departments
     *
     * @param \Vlabs\AddressBundle\Entity\Department $departments
     */
    public function removeDepartment(\Vlabs\AddressBundle\Entity\Department $departments)
    {
        $this->departments->removeElement($departments);
    }

    /**
     * Get departments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Set country
     *
     * @param \Vlabs\AddressBundle\Entity\Country $country
     * @return Region
     */
    public function setCountry(\Vlabs\AddressBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Vlabs\AddressBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
