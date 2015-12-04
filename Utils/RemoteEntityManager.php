<?php

namespace YWC\RemoteBundle\Utils;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use YWC\RemoteBundle\Repository\Repository;

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

    public function clearCache($limit)
    {
        $entities = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        $entities = array_filter($entities, function($entity) {
            $reader = new AnnotationReader();
            $class = new \ReflectionClass($entity);
            if(!$reader->getClassAnnotation($class, 'YWC\\RemoteBundle\\Annotation\\Remote')) return false;
            if(!$class->isSubclassOf('YWC\\RemoteBundle\\Entity\\RemoteEntity')) return false;
            return true;
		});   

        foreach($entities as $entity) {
            $qb = $this->em->createQueryBuilder();
            $qb->delete($entity, 'e')
               ->where('e.remoteSync < :limit')
               ->setParameter('limit', $limit)
               ->getQuery()
               ->getResult()
            ;
        }        
    }

    public function attach($entity, $api = null)
    {
        $reader = new AnnotationReader();
        $class = new \ReflectionClass($entity);
        foreach($class->getProperties() as $property) {
            if($relation = $reader->getPropertyAnnotation($property, 'YWC\\RemoteBundle\\Annotation\\RemoteRelation')) {
                $id = call_user_func_array(array($entity, 'get'.ucfirst($property->getName())), array());
                call_user_func_array(array($entity, 'set'.ucfirst($property->getName())), array($this->getRepository($relation->className, $api)->find($id)));
            }
        }
    }

    public function detach($entity)
    {
        $reader = new AnnotationReader();
        $class = new \ReflectionClass($entity);
        foreach($class->getProperties() as $property) {
            if($relation = $reader->getPropertyAnnotation($property, 'YWC\\RemoteBundle\\Annotation\\RemoteRelation')) {
                $attachedEntity = call_user_func_array(array($entity, 'get'.ucfirst($property->getName())), array());
                call_user_func_array(array($entity, 'set'.ucfirst($property->getName())), array($attachedEntity->getRemoteId()));
            }
        }
    }
}