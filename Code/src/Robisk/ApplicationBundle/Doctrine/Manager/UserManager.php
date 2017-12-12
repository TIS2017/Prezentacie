<?php

namespace Robisk\ApplicationBundle\Doctrine\Manager;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Persistence\ObjectManager;

class UserManager extends CommonManager 
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
}