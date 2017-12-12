<?php

namespace Robisk\ApplicationBundle\Controller\Admin;

use Robisk\ApplicationBundle\Controller\BaseController;

class StaticAdminController extends BaseController
{
    public function indexAction()
    {
        $subjectManager = $this->get('manager_subject');
        $activeSubjects = $subjectManager->getActiveSubjects();
        $user = $this->getUser();

        return $this->render(
            'RobiskApplicationBundle:Static/Admin:index.html.twig',
            array(
                'activeSubjects' => $activeSubjects,
                'user'           => $user,
            )
        );
    }
}