<?php

namespace Robisk\ApplicationBundle\Twig;

use Robisk\ApplicationBundle\Entity\Subject;
use Robisk\ApplicationBundle\Entity\User;
use Robisk\ApplicationBundle\Entity\UserSubjectLookup;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubjectExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('statusFromUSL', array($this, 'getStatusFromUSL')),
        );
    }


    public function getStatusFromUSL(User $user, Subject $subject)
    {
        /** @var array $usl */
        $usl = $this->subjectManager->getRepository()->getStatusFromUSL($user, $subject);

        return reset($usl)->getStatus();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'robisk_subject_extension';
    }
}
