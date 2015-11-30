<?php

namespace YWC\CommonBundle\Remote\Entity;

use YWC\CommonBundle\Remote\Entity;

class User extends Entity
{
    public static $civilities = array(
        0 => 'Mme',
        1 => 'M.',
    );
    
    protected $client;

    protected $email;

    protected $firstName;

    protected $lastName;

    protected $gender;

    protected $phone;

    private $mailCounter;

    public function __construct($url, $id)
    {
        $this->remoteEntityPrivateAttributes[] = 'client';
        $this->remoteEntityPrivateAttributes[] = 'mailCounter';
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

    public function getCivility()
    {
        return self::$civilities[(int)$this->getGender()];
    }

    public function getFullName()
    {
        return $this->getCivility().' '.$this->getFirstName().' '.$this->getLastName();
    }
    
    public function upMailCounter($mailCounter)
    {
        if(is_null($this->mailCounter)) $this->mailCounter = $mailCounter;

        return $this;
    }

    public function incMailCounter()
    {
        $this->mailCounter++;

        return $this;
    }

    public function getMailCounter()
    {
        return $this->mailCounter;
    }
        
}
