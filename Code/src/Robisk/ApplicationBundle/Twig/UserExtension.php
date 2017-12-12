<?php

namespace Robisk\ApplicationBundle\Twig;

use Robisk\ApplicationBundle\Entity\Subject;
use Robisk\ApplicationBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserExtension extends \Twig_Extension
{
    private $container;
    private $userManager;
    private $subjectManager;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->userManager = $container->get('manager_user');
        $this->subjectManager = $container->get('manager_subject');
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isInSubject', array($this, 'isInSubject')),
            new \Twig_SimpleFunction('getUnreadAnnouncements', array($this, 'getUnreadAnnouncements')),
        );
    }

    /**
     * Check if user is in subject
     *
     * @param User $user
     * @param Subject $subject
     * @return boolean
     */
    public function isInSubject(User $user, Subject $subject)
    {
        $result = false;

        if (in_array($subject, $user->getMySubjects())) {
            $result = true;
        }

        return $result;
    }

    /**
     * Get count of unread announcements
     *
     * @param User $user
     * @param Subject $subject
     * @return integer
     */
    public function getUnreadAnnouncements(User $user, Subject $subject)
    {
        return $this->userManager->getRepository()->getUnreadAnnouncements($user, $subject);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'robisk_user_extension';
    }
}
