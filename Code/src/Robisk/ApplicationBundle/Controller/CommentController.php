<?php

namespace Robisk\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Robisk\ApplicationBundle\Form\Type\CommentType;

class CommentController extends BaseController
{
    public function announcementUserForumAction($id, $announcementId){
        $annManager = $this->get('manager_announcement');
        $commentManager = $this->get('manager_announcement_comment');
        $subjectManager = $this->get('manager_subject');

        $request = $this->get('request');
        $announcement = $annManager->findOneBy(array('id' => $announcementId));
        $comment = $commentManager->create();
        $user = $this->getUser();
        $subject = $subjectManager->findOneBy(array('id' => $id));

        $form = $this->createForm(new CommentType(), $comment, array('csrf_protection' => false));

        $url = $this->generateUrl('route_subject_announcements_forum',
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
            'RobiskApplicationBundle:Comment:announcementUserForum.html.twig',
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

    public function announcementUserForumDeleteAction($id, $announcementId, $commentId){
        $commentManager = $this->get('manager_announcement_comment');
        $comment = $commentManager->findOneBy(array('id' => $commentId));
        $commentManager->delete($comment);

        $url = $this->generateUrl('route_subject_announcements_forum',
            array(
                'id'             => $id,
                'announcementId' => $announcementId
            ));
        $response = new RedirectResponse($url);
        return $response;
    }

    public function teacherPresentationUserForumAction($id, $presentationId){
        $presentationManager = $this->get('manager_teacher_presentation');
        $commentManager = $this->get('manager_teacher_presentation_comment');
        $subjectManager = $this->get('manager_subject');
        $request = $this->get('request');

        $subject = $subjectManager->findOneBy(array('id' => $id));
        $presentation = $presentationManager->findOneBy(array('id' => $presentationId));
        $comment = $commentManager->create();
        $user = $this->getUser();

        $form = $this->createForm(new CommentType(), $comment, array('csrf_protection' => false));

        $url = $this->generateUrl('route_subject_teacher_presentations_forum',
            array(
                'id'             => $id,
                'presentationId' => $presentationId
            ));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $comment->setUser($this->getUser());
                $comment->setPresentation($presentation);
                $comment->setDate(new \DateTime("now"));

                $commentManager->update($comment);

                $response = new RedirectResponse($url);
                return $response;
            }
        }

        return $this->render(
            'RobiskApplicationBundle:Comment:teacherPresentationUserForum.html.twig',
            array(
                'presentation' => $presentation,
                'subject' => $subject,
                'form' => $form->createView(),
                'url' => $url,
                'user' => $user
            )
        );
    }

    public function teacherPresentationUserForumDeleteAction($id, $presentationId, $commentId){
        $commentManager = $this->get('manager_teacher_presentation_comment');
        $comment = $commentManager->findOneBy(array('id' => $commentId));
        $commentManager->delete($comment);

        $url = $this->generateUrl('route_subject_teacher_presentations_forum',
            array(
                'id'             => $id,
                'presentationId' => $presentationId
            ));
        $response = new RedirectResponse($url);
        return $response;
    }

    public function userPresentationForumAction($id, $presentationId){
        $presentationManager = $this->get('manager_presentation');
        $commentManager = $this->get('manager_user_presentation_comment');
        $subjectManager = $this->get('manager_subject');
        $request = $this->get('request');

        $subject = $subjectManager->findOneBy(array('id' => $id));
        $presentation = $presentationManager->findOneBy(array('id' => $presentationId));
        $comment = $commentManager->create();
        $user = $this->getUser();

        $form = $this->createForm(new CommentType(), $comment, array('csrf_protection' => false));

        $url = $this->generateUrl('route_subject_presentation_forum',
            array(
                'id'             => $id,
                'presentationId' => $presentationId
            ));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $comment->setUser($this->getUser());
                $comment->setPresentation($presentation);
                $comment->setDate(new \DateTime("now"));

                $commentManager->update($comment);

                $response = new RedirectResponse($url);
                return $response;
            }
        }

        return $this->render(
            'RobiskApplicationBundle:Comment:userPresentationUserForum.html.twig',
            array(
                'presentation' => $presentation,
                'subject' => $subject,
                'form' => $form->createView(),
                'url' => $url,
                'user' => $user
            )
        );
    }

    public function userPresentationUserForumDeleteAction($id, $presentationId, $commentId)
    {
        $commentManager = $this->get('manager_user_presentation_comment');
        $comment = $commentManager->findOneBy(array('id' => $commentId));
        $commentManager->delete($comment);

        $url = $this->generateUrl('route_subject_presentation_forum',
            array(
                'id' => $id,
                'presentationId' => $presentationId
            ));
        $response = new RedirectResponse($url);
        return $response;
    }

}