<?php

namespace YWC\RemoteBundle\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;

class Repository
{
    $className;

    $em;

    $api;

    $repository;    

    public function __construct(EntityManager $em, $api, $className)
    {
        $reader = new AnnotationReader();
        if(!$reader->getClassAnnotation(new \ReflectionClass($className), 'YWC\\RemoteBundle\\Annotations\\Remote')) {
			throw new Exception(sprintf('Entity class %s does not have required annotation Remote', $className));
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
        return array_pop(explode('\\', $this->className));
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
        return $this->fromJson(json_decode(file_get_contents($this->getUrl($id))));
    }

    private function fromJson($json)
    {
        $reader = new AnnotationReader();
        $class = new \ReflectionClass($this->className);
        $entity = $class->newInstanceWithoutConstructor();

        $classname = $this->getClassNameNoNS();
        foreach($json->$class as $key => $val) {
            if($entity->hasMethod('set'.ucfirst($key))) {
                call_user_func_array(array($entity, 'set'.ucfirst($key)), array($val));
            }
        }

        $em->persist($entity);
        $me->flush();

        return $entity;
    }

}