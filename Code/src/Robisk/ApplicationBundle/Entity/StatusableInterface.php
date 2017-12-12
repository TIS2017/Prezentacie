<?php

namespace Robisk\ApplicationBundle\Entity;

interface StatusableInterface
{

    // status soft deleted
    const SOFT_DELETED = 0;
    
    // status soft created
    const SOFT_CREATED = 1;

    // status entity OK -- regular state of entity
    const NORMAL = 2;

    public function setStatus($status);

    public function getStatus();
}
