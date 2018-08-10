<?php

namespace Vlabs\AddressBundle\DTO;

use Vlabs\AddressBundle\Entity\Country;

class CountryDTO
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $abbreviation;

    /**
     * @var string
     */
    private $name;

    /**
     * @param Country $country
     *
     * @return CountryDTO
     */
    public function fillFromEntity(Country $country)
    {
        $this->id           = $country->getId();
        $this->abbreviation = $country->getAbbreviation();
        $this->name         = $country->getName();

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
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}