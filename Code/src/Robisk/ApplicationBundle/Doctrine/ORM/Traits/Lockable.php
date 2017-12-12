<?php

namespace Robisk\ApplicationBundle\Doctrine\ORM\Traits;


use Doctrine\Common\Collections\ArrayCollection;
use Robisk\ApplicationBundle\Entity\StatusableInterface as Status;

trait Lockable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    protected $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    protected $locked = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lock_created", type="datetime", nullable=true)
     */
    protected $lockCreated;

    /**
     * Set status
     *
     * @param string $status
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get lock status
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Lock entity and set locked time
     *
     * @return $this
     */
    public function lockEntity()
    {
        if ($this->locked !== true) {
            $this->locked = true;
            $this->lockCreated = new \DateTime();
        }

        return $this;
    }

    /**
     * Unlock entity and reset locked time
     *
     * @return $this
     */
    public function unlockEntity()
    {
        if ($this->locked !== false) {
            $this->locked = false;
            $this->lockCreated = null;
        }

        return $this;
    }

    /**
     * Get time when the entity was locked
     *
     * @return \DateTime
     */
    public function getLockCreated()
    {
        return $this->lockCreated;
    }

    /**
     * Resets/nulls entity to SOFT_CREATED state
     *
     */
    public function reset()
    {
        foreach ($this as $key => $field) {
            if ($key !== 'id') {
                if ($this->$key instanceof ArrayCollection) {
                    $this->$key = new ArrayCollection();
                } else {
                    $this->$key = null;
                }
            }
        }

        $this->locked = false;
        $this->status = Status::SOFT_CREATED;
    }
}