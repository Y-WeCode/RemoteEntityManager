<?php

namespace YWC\RemoteBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class RemoteRelation extends Annotation
{
    public $className;
}