<?php

namespace Robisk\ApplicationBundle\Doctrine\Manager;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Persistence\ObjectManager;
use Robisk\ApplicationBundle\Entity\Attendance;

class AttendanceManager extends CommonManager 
{
	/**
     * Constructor.
     *
     * @param EntityManager           $om
     * @param string                  $class
     * @param ServiceContainer        $container
     */
    public function __construct($om, $class, $container)
    {
        parent::__construct($om, $class);
    }

    public function getUnlockedAttendance($subjectId)
    {
        $attendance = $this->findOneBy(array(
                'subject' => $subjectId,
                'status'  => Attendance::STATUS_UNLOCKED
            ));

        return $attendance;
    }
}