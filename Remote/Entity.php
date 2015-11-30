<?php

namespace YWC\RemoteBundle\Remote;

class Entity
{
    
    protected $id;

    private $remoteEntityUrl;

    private $remoteEntityLoaded = false;

    private $remoteEntityType;

    // array of: array('attribute' => 'str', 'repository' => 'str')
    protected $remoteEntityAttached = array();

    protected $remoteEntityPrivateAttributes = array(
        'remoteEntityUrl',
        'remoteEntityType',
        'remoteEntityLoaded',
        'remoteEntityAttributes',
        'remoteEntityPrivateAttributes',
        'remoteEntityAttached',
        'id',
    );

    private $remoteEntityAttributes = array();

    public function __construct($url, $id)
    {
        $this->id = $id;
        $tmp = explode('\\', get_class($this));
        $this->remoteEntityType = strtolower(array_pop($tmp));
        $this->remoteEntityUrl = $url;
        foreach($this as $key => $value) {
            if(!in_array($key, $this->remoteEntityPrivateAttributes)) $this->remoteEntityAttributes[] = $key;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    private function getUrl()
    {
        return $this->remoteEntityUrl.$this->remoteEntityType.'/'.$this->getId();
    }

    public function getApi()
    {
        return substr($this->remoteEntityUrl, 0, -7);
    }

    protected function load()
    {
        $this->fromJson(json_decode(file_get_contents($this->getUrl())));        
    }

    public function fromJson($json)
    {
        $ret = $this->remoteEntityType;
        foreach($this->remoteEntityAttributes as $key) {            
            $this->$key = $json->$ret->$key;
        }
        $this->remoteEntityLoaded = true;
    }

    public function remoteEntityGetAttached()
    {
        return $this->remoteEntityAttached;
    }

    public function  __call($name, $args)
    {
        if(strpos($name, 'get') === 0) {
            $attr = lcfirst(substr($name, 3));
            if(!in_array($attr, $this->remoteEntityAttributes)) trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
            if(!$this->remoteEntityLoaded) $this->load();
        
            return $this->$attr;
        }
        elseif(strpos($name, 'attach') === 0) {
            $attr = lcfirst(substr($name, 6));
            if(!in_array($attr, array_map(function ($item) {
                return $item['attribute'];
            }, $this->remoteEntityAttached))) trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
            $this->$attr = $args[0];
        }
        else trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
    }

    public function __toString()
    {
        return $this->getName();
    }
    
}
