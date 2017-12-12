<?php

namespace Robisk\ApplicationBundle\Doctrine\Manager;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Robisk\ApplicationBundle\Doctrine\ORM\Traits\Crud;

class CommonManager
{
    use Crud;

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
}