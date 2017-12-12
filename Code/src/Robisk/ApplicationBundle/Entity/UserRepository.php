<?php

namespace Robisk\ApplicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package Robisk\ApplicationBundle\Entity
 *
 * @author Robert Stribrnsky (robo.stribrnsky@gmail.com)
 */
class UserRepository extends EntityRepository
{
    public function findAllUsers()
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query = $queryBuilder->add('select', 'u')
            ->add('from', 'RobiskApplicationBundle:User u')
            ->add('where', 'u.role = 1')
            ->add('orderBy', 'u.lastName asc')
            ->getQuery();
        return $query->getResult();
    }

    public function getUnreadAnnouncements(User $user, Subject $subject)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $query = $queryBuilder->add('select', 'a')
            ->add('from', 'RobiskApplicationBundle:Announcement a')
            ->add('where', 'a.updated > :lastVisited AND a.subject = :subject')
            ->setParameter(
                'lastVisited',
                $user->getLastVisitedAnnouncements()
            )
            ->setParameter(
                'subject',
                $subject
            )
            ->getQuery();
        return $query->getResult();
    }
}
