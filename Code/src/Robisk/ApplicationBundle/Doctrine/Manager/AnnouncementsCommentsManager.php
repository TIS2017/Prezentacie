<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 26.01.2018
 * Time: 13:30
 */

namespace Robisk\ApplicationBundle\Doctrine\Manager;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Persistence\ObjectManager;

class AnnouncementsCommentsManager extends CommonManager
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