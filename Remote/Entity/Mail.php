<?php

namespace YWC\RemoteBundle\Remote\Entity;

use YWC\RemoteBundle\Remote\Entity;

class Mail extends Entity
{
    protected $date;

    protected $type;

    protected $matching;

    protected $company;

    protected $project;

    protected $remoteEntityAttached = array(
        array('attribute' => 'project', 'repository' => 'YWCUserBundle:Project'),
    );

    public function __toString()
    {
        return $this->id.' - '.$this->company;
    }

    public function getDate()
    {
        if(is_null($this->date)) return null;

        if(! $this->date instanceof \DateTime) $this->date = new \DateTime($this->date);

        return $this->date;
    }
        
}
