<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package Robisk\ApplicationBundle\Entity
 *
 * @author Robert Stribrnsky (robo.stribrnsky@gmail.com)
 */
class SubjectRepository extends EntityRepository
{
    public function getActiveSubjects()
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $query = $queryBuilder->add('select', 's')
            ->add('from', 'RobiskApplicationBundle:Subject s')
            ->add('where', 's.status = :status')
            ->add('orderBy', 's.name asc')
            ->setParameter(
                'status',
                Subject::STATUS_ACTIVE
            )
            ->getQuery();
        return $query->getResult();
    }

    public function getStatusFromUSL(User $user, Subject $subject)
    {
        $queryBuilder = $this->createQueryBuilder('usl');
        $query = $queryBuilder->add('select', 'usl')
            ->add('from', 'RobiskApplicationBundle:UserSubjectLookup usl')
            ->add('where', 'usl.user = :user AND usl.subject = :subject')
            ->setParameter(
                'user',
                $user
            )
            ->setParameter(
                'subject',
                $subject
            )
            ->getQuery();
        return $query->getResult();
    }
}
