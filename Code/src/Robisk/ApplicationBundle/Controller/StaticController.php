<?php

namespace Robisk\ApplicationBundle\Controller;

class StaticController extends BaseController
{
    public function indexAction()
    {
        $afm = $this->get('manager_announcement_frontend');
        $announcements = $afm->findBy(array(), array('updated' => 'DESC'));
        $user = $this->getUser();
    	
        return $this->render(
            'RobiskApplicationBundle:Static:index.html.twig',
            array(
                'ajax_url'      => $this->generateUrl('route_home', array()),
                'announcements' => $announcements,
                'user'          => $user,
            )
        );
    }
}