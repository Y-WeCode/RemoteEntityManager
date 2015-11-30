<?php

namespace YWC\RemoteBundle\Remote;

use Doctrine\ORM\EntityManager;

class RemoteEntityManager
{

    private $url;

    private $em;

    private $loaded = array();

    public function __construct($url, EntityManager $em)
    {
        $this->url = $url.'entity/';
        $this->em = $em;
    }

    public function setRemote($url)
    {
        $this->url = $url.'entity/';
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getApi()
    {
        return substr($this->url, 0, -7);
    }

    public function get($type, $id = null, $url = null)
    {
        $type = strtolower($type);

        if(is_null($id) && !is_null($url)) {

            return $this->loaded[$type][$url];
        }

        if(is_null($id)) {
            $result = array();
            if(isset($this->loaded[$type])) {
                foreach($this->loaded[$type] as $urls) {
                    foreach($urls as $item) $result[] = $item;
                }
            }

            return $result;
        }

        if(is_null($url)) {
            if(!isset($this->loaded[$type][$this->url][$id])) {        
                $reflect = new \ReflectionClass('YWC\CommonBundle\Remote\Entity\\'.ucfirst($type));        
                $this->loaded[$type][$this->url][$id] = $reflect->newInstanceArgs(array($this->url, $id));
            }

            return $this->loaded[$type][$this->url][$id];
        }
    }

    public function jsonLoad($type, $json)
    {
        $type = strtolower($type);
        $json = json_decode($json)->{$type.'s'};
        $reflect = new \ReflectionClass('YWC\CommonBundle\Remote\Entity\\'.ucfirst($type));
        foreach($json as $item) {
            if(!isset($this->loaded[$type][$this->url][$item->id])) {                
                $item = (object) array($type => $item);
                $newItem = $reflect->newInstanceArgs(array($this->url, $item->$type->id));
                $newItem->fromJson($item);
                $this->loaded[$type][$this->url][$item->$type->id] = $newItem;
            }
        }
    }

    public function attachLocalEntities($remoteEntity)
    {
        foreach($remoteEntity->remoteEntityGetAttached() as $entity) {
            $localEntity = $this->em->getRepository($entity['repository'])->find(call_user_func_array(array($remoteEntity, 'get'.ucfirst($entity['attribute'])), array()));
            call_user_func_array(array($remoteEntity, 'attach'.ucfirst($entity['attribute'])), array($localEntity));
        }
    }
}
