<?php

namespace Robisk\ApplicationBundle\Doctrine\ORM\Traits;

use Doctrine\Common\Persistence\ObjectManager;

trait Crud
{
    protected $objectManager;
    protected $class;
    protected $repository;

    protected $isSoftlyDeletable = false;

    public function init(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);

        if (isset($metadata->fieldMappings['deleted'])) {
            $this->isSoftlyDeletable = true;
        }

        $this->class = $metadata->getName();
    }

    /**
     * Returns entity manager
     * @return Doctrine\ORM\EntityManager om
     */
    public function getEntityManager()
    {
        return $this->objectManager;
    }

    public function getRepository()
    {
        return $this->repository;
    }


    /**
     * Returns an empty entity instance
     *
     * @return entity
     */
    public function create()
    {
        $class = $this->getClass();
        return new $class;
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (!isset($criteria['deleted']) && $this->isSoftlyDeletable) {
            $criteria['deleted'] = 0;
        }
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria)
    {
        if (!isset($criteria['deleted']) && $this->isSoftlyDeletable) {
            $criteria['deleted'] = 0;
        }

        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        if ($this->isSoftlyDeletable) {
            return $this->repository->findBy(array('deleted' => 0));
        } else {
            return $this->repository->findAll();
        }
    }

    /**
     * {@inheritDoc}
     */

    public function delete($entity, $soft = false)
    {
        if ($soft && method_exists($entity, 'setDeleted')) {
            $entity->setDeleted(true);
            $this->update($entity, true);
        } else {
            $this->objectManager->remove($entity);
            $this->objectManager->flush();
        }
    }

    /**
     * @param UserInterface $user
     * @param Boolean       $andFlush Whether to flush the changes (default true)
     */
    public function update($entity, $andFlush = true)
    {
        $this->objectManager->persist($entity);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    public function flush()
    {
        return $this->objectManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function reload($entity)
    {
        $this->objectManager->refresh($entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}