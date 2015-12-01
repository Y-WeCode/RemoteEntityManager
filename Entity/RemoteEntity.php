<?php

namespace YWC\RemoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class RemoteEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="remoteId", type="integer")
     */
    protected $remoteId;

    /**
     * @var string
     *
     * @ORM\Column(name="remoteApi", type="string", length=255)
     */
    protected $remoteApi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="remoteSync", type="datetime")
     */
    protected $remoteSync;

    private $attachedRemotes;

    public function getRemoteId()
    {
        return $this->remoteId;
    }

    public function setRemoteId($id)
    {
        $this->remoteId = $id;

        return $this;
    }

    public function getRemoteApi()
    {
        return $this->remoteApi;
    }

    public function setRemoteApi($remoteApi)
    {
        $this->remoteApi = $remoteApi;

        return $this;
    }

    public function getRemoteSync()
    {
        return $this->remoteSync;
    }

    public function setRemoteSync(\DateTime $remoteSync)
    {
        $this->remoteSync = $remoteSync;

        return $this;
    }

    /*
    public function __call($name, $args)
    {
        if(strpos($name, 'getRemote') !== 0) trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);

        if(is_null($attachedRemote)) trigger_error('The remote attributes have not been attached by the manager', E_USER_ERROR);
        
        $attr = lcfirst(substr($name, 9));
        $class = new \ReflectionClass(__CLASS__);
        if(!$class->hasProperty($attr)) trigger_error('Class has not attribute '.__CLASS__.'::'.$attr, E_USER_ERROR);
        
        $prop = new \ReflectionProperty(__CLASS__, $attr);
        $reader = new AnnotationReader();
        $remote = $reader->getPropertyAnnotation($reflectionProp, 'YWC\\RemoteBundle\\Annotation\\Remote');
        if(!$remote) trigger_error('Class attribute '.__CLASS__.'::'.$attr.' has not the @Remote annotation', E_USER_ERROR);

        return $this->attachedRemotes[$attr];
    }

    public function attachRemotes(array $remotes)
    {
        $this->attachedRemotes = $remotes;

        return $this;
    }
    */

}