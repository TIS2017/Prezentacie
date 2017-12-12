<?php

namespace Robisk\ApplicationBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Robisk\ApplicationBundle\Entity\User;

class Registration
{
    /**
     * @Assert\Type(type="Robisk\ApplicationBundle\Entity\User")
     * @Assert\Valid()
     */
    protected $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    /**
     * Set user
     *
     * @param User $user
     * @return Registration
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set terms accepted
     */
    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    /**
     * Get terms accepted
     */
    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (Boolean) $termsAccepted;
    }
}