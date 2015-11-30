<?php

namespace YWC\RemoteBundle\Remote\Entity;

use YWC\RemoteBundle\Remote\Entity;

class Geoloc extends Entity
{
    protected $countryName;

    protected $regionName;

    protected $departmentName;

    protected $departmentId;

    protected $cityName;

    protected $cityNameAi;

    protected $zipCode;

    protected $lat;

    protected $long;

    protected $typeGeo;
    
    public function __toString()
    {
        return $this->getCityNameAi();
    }

    public function getName()
    {
        if($this->typeGeo === 'region') return $this->regionName;
        if($this->typeGeo === 'department') return $this->departmentName;
        return $this->cityName;
    }
}
