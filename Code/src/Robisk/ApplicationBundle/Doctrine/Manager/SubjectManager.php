<?php

namespace Robisk\ApplicationBundle\Doctrine\Manager;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Persistence\ObjectManager;
use Robisk\ApplicationBundle\Entity\Subject;

class SubjectManager extends CommonManager 
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

    /** 
     * Sort array of objects by field.
     *
     * @param array $objects Array of objects to sort.
     * @param string $on Name of field.
     * @param string $order (ASC|DESC)
     */
    function sort_on_field(&$objects, $on, $order = 'ASC') {
        $comparer = ($order === 'DESC')
            ? "return -strcmp(\$a->{$on},\$b->{$on});"
            : "return strcmp(\$a->{$on},\$b->{$on});";
        usort($objects, create_function('$a,$b', $comparer));
    }

    public function getActiveSubjects()
    {
        $subjects = $this->findBy(array(
                'status' => Subject::STATUS_ACTIVE
            ));

        $this->sort_on_field($subjects, 'getName()', 'ASC');

        return $subjects;
    }
}