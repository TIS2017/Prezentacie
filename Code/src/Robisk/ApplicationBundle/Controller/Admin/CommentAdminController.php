<?php

namespace Robisk\ApplicationBundle\Controller\Admin;

use http\Env\Response;
use Robisk\ApplicationBundle\Controller\BaseController;
use Robisk\ApplicationBundle\Form\Type\CommentType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CommentAdminController extends BaseController
{
    public function announcementAction($id, $announcementId){
        $annManager = $this->get('manager_announcement');
        $commentManager = $this->get('manager_announcement_comment');
        $subjectManager = $this->get('manager_subject');

        $request = $this->get('request');
        $announcement = $annManager->findOneBy(array('id' => $announcementId));
        $comment = $commentManager->create();
        $user = $this->getUser();
        $subject = $subjectManager->findOneBy(array('id' => $id));

        $form = $this->createForm(new CommentType(), $comment, array('csrf_protection' => false));

        $url = $this->generateUrl('route_admin_announcement_forum',
            array(
                'id'             => $id,
                'announcementId' => $announcementId
            ));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $comment->setUser($this->getUser());
                $comment->setAnnouncement($announcement);
                $comment->setDate(new \DateTime("now"));

                $commentManager->update($comment);

                $response = new RedirectResponse($url);
                return $response;
            }
        }

        return $this->render(
            'RobiskApplicationBundle:Comment/Admin:announcementComment.html.twig',
            array(
                'id' => $announcement->getSubject()->getId(),
                'announcement' => $announcement,
                'subject' => $announcement->getSubject(),
                'form' => $form->createView(),
                'url' => $url,
                'user' => $user
            )
        );
    }

    public function announcementDeleteAction($id, $announcementId, $commentId){
        $commentManager = $this->get('manager_announcement_comment');
        $comment = $commentManager->findOneBy(array('id' => $commentId));
        $commentManager->delete($comment);

        $url = $this->generateUrl('route_admin_announcement_forum',
            array(
                'id'             => $id,
                'announcementId' => $announcementId
            ));
        $response = new RedirectResponse($url);
        return $response;
    }

    public function announcementSwitchValuatedAction($id, $announcementId, $commentId){
        $commentManager = $this->get('manager_announcement_comment');
        $comment = $commentManager->findOneBy(array('id' => $commentId));

        $comment->setValuated(($comment->getValuated() + 1) % 2);
        $commentManager->update($comment);

        $url = $this->generateUrl('route_admin_announcement_forum',
            array(
                'id'             => $id,
                'announcementId' => $announcementId
            ));
        $response = new RedirectResponse($url);
        return $response;
    }
}
