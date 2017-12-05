<?php

namespace Robisk\ApplicationBundle\Doctrine\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Robisk\ApplicationBundle\Entity\StatusableInterface as Status;

class ApplicationCommonManager extends CommonManager
{
    /**
     * Constructor.
     *
     * @param ObjectManager           $om
     * @param string                  $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->init($om, $class);
    }

    /**
     * @return entity
     */
    public function createAndPersist()
    {
        $entity = $this->findOneBy(array('status' => Status::SOFT_CREATED));
        if (empty($entity)) {
            $entity = $this->create();
            $entity->setStatus(Status::SOFT_CREATED);
            parent::update($entity);
        }
        return $entity;
    }

    /**
     * {@inheritdoc }
     */
    public function update($entity, $andFlush = true)
    {
        if ($entity instanceof Status) {
            $entity->setStatus(Status::NORMAL);
        }

        parent::update($entity, $andFlush);
    }
}