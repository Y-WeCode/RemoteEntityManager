<?php

namespace YWC\RemoteBundle\Utils;

use Doctrine\ORM\EntityManager;

class RemoteEntityManager
{
    private $api;

    private $em;

    public function __construct(EntityManager $em, $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    public function getRepository($className, $api = null)
    {
        if(is_null($api)) $api = $this->api;
        
        return new Repository($this->em, $api, $className);
    }
}