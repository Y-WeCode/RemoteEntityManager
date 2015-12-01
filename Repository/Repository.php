<?php

namespace YWC\RemoteBundle\Repository;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;

class Repository
{
    private $className;

    private $em;

    private $api;

    private $repository;    

    public function __construct(EntityManager $em, $api, $className)
    {
        $reader = new AnnotationReader();
        $class = new \ReflectionClass($className);
        if(!$reader->getClassAnnotation($class, 'YWC\\RemoteBundle\\Annotation\\Remote')) {
			throw new \Exception(sprintf('Entity class %s does not have required annotation Remote', $className));
		}
        if(!$class->is_subclass_of('YWC\\RemoteBundle\\Entity\\RemoteEntity')) {
            throw new \Exception(sprintf('Entity class %s does not extend the required mapped superclass RemoteEntity', $className));
		}
        
        $this->em = $em;
        $this->api = $api;        
        $this->className = $className;
        $this->repository = $em->getRepository($className);
    }

    private function getBaseUrl()
    {
        return $this->api.'entity/'.strtolower($this->getClassNameNoNS()).'/';
    }

    private function getClassNameNoNS()
    {
        $tmp = explode('\\', $this->className);
        
        return array_pop($tmp);
    }

    private function getUrl($id)
    {
        return $this->getBaseUrl().$id;
    }

    public function find($id)
    {
        $cache = $this->repository->find($id);
        
        if(!is_null($cache)) return $cache;

        return $this->load($id);
    }

    public function load($id)
    {
        echo $this->getUrl($id);
        return $this->fromJson(json_decode(file_get_contents($this->getUrl($id))), $id);
    }

    private function fromJson($json, $id)
    {
        $reader = new AnnotationReader();
        $class = new \ReflectionClass($this->className);
        $entity = $class->newInstanceWithoutConstructor();

        $entity->setId($id);

        $className = $this->getClassNameNoNS();
        foreach($json->$className as $key => $val) {
            if($class->hasMethod('set'.ucfirst($key))) {
                call_user_func_array(array($entity, 'set'.ucfirst($key)), array($val));
            }
        }

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

}