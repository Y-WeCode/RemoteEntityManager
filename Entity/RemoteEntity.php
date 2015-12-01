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
     * @ORM\Column(name="remoteApi", type="integer")
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
}