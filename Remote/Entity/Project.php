<?php

namespace YWC\RemoteBundle\Remote\Entity;

use YWC\API\AdminBundle\Entity\Client;
use YWC\RemoteBundle\Remote\Entity;

class Project extends Entity
{
    protected $name;

    protected $trade;

    protected $geoloc;

    protected $geolocRadius;

    protected $client;

    protected $strict;

    protected $user;

    protected $CV;

    protected $LDM;

    public function __construct($url, $id)
    {
        $this->remoteEntityPrivateAttributes[] = 'client';
        parent::__construct($url, $id);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    public function getGeoloc()
    {
        return $this->geoloc;
    }

    public function setGeoloc($geoloc)
    {
        $this->geoloc = $geoloc;

        return $this;
    }

    public function getCV()
    {
        $this->load();
        
        return $this->CV;
    }

    public function getLDM()
    {
        $this->load();
        
        return $this->LDM;
    }

    public function isValid()
    {        
        return $this->getCV() && $this->getLDM();
    }    
        
}
