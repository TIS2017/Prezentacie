<?php

namespace Robisk\ApplicationBundle\Controller\Admin;

use Robisk\ApplicationBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Robisk\ApplicationBundle\Form\Type\AnnouncementType;

class AnnouncementAdminController extends BaseController
{
    public function indexAction($id)
    {
        $subjectManager = $this->get('manager_subject');
        $annManager = $this->get('manager_announcement');
        $subject = $subjectManager->findOneBy(array('id' => $id));
        $announcements = $subject->getAnnouncements();
        $user = $this->getUser();

        return $this->render(
            'RobiskApplicationBundle:Announcement/Admin:index.html.twig',
            array(
                'user'          => $user,
                'announcements' => $announcements,
                'subject'       => $subject
            )
        );
    }

    public function addAction($id)
    {
        $request = $this->get('request');
        $subjectManager = $this->get('manager_subject');
        $annManager = $this->get('manager_announcement');
        $subject = $subjectManager->findOneBy(array('id' => $id));
        $user = $this->getUser();
        $announcement = $annManager->create();

        $form = $this->createForm(new AnnouncementType(), $announcement, array('csrf_protection' => false));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $announcement->setSubject($subject);
                $annManager->update($announcement);

                $url = $this->generateUrl('route_admin_announcements',
                    array(
                            'id'             => $subject->getId(),
                    ));

                $response = new RedirectResponse($url);
                return $response;
            }
        }

        $url = $this->generateUrl('route_admin_announcement_add', array('id' => $subject->getId()));

        return $this->render(
            'RobiskApplicationBundle:Announcement/Admin:edit.html.twig',
            array(
                'form'    => $form->createView(),
                'user'    => $user,
                'action'  => 'add',
                'url'     => $url,
                'subject' => $subject,
            )
        );
    }

    public function editAction($id, $announcementId)
    {
        $request = $this->get('request');
        $subjectManager = $this->get('manager_subject');
        $annManager = $this->get('manager_announcement');
        $user = $this->getUser();
        $announcement = $annManager->findOneBy(array('id' => $announcementId));

        $form = $this->createForm(new AnnouncementType(), $announcement, array('csrf_protection' => false));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $announcement->setUpdated(new \DateTime("now"));
                $announcement->increaseUpdatedCount();
                $annManager->update($announcement);
            }
        }

        $url = $this->generateUrl('route_admin_announcement_edit',
            array(
                    'id'             => $announcement->getSubject()->getId(),
                    'announcementId' => $announcement->getId(),
            ));

        return $this->render(
            'RobiskApplicationBundle:Announcement/Admin:edit.html.twig',
            array(
                'form'         => $form->createView(),
                'user'         => $user,
                'action'       => 'edit',
                'announcement' => $announcement,
                'url'          => $url,
                'subject'      => $announcement->getSubject()
            )
        );
    }

    public function deleteAction($id, $announcementId)
    {
        $subjectManager = $this->get('manager_subject');
        $annManager = $this->get('manager_announcement');
        $user = $this->getUser();
        $announcement = $annManager->findOneBy(array('id' => $announcementId));
        $subject = $announcement->getSubject();

        $annManager->delete($announcement);

        $url = $this->generateUrl('route_admin_announcements', array('id' => $id));

        $responce = new RedirectResponse($url);
        return $responce;
    }

  public function forumAction($id, $announcementId){
    $subjectManager = $this->get('manager_subject');
    $annManager = $this->get('manager_announcement');
    $user = $this->getUser();
    $announcement = $annManager->findOneBy(array('id' => $announcementId));

    $form = $this->createForm(new AnnouncementType(), $announcement, array('csrf_protection' => false));

    return $this->render(
      'RobiskApplicationBundle:Announcement/Admin:diskusia.html.twig',
      array(
        'id' => $announcement->getSubject()->getId(),
        'announcement' => $announcement,
        'announcementId' => $announcement->getId(),
        'subject' => $announcement->getSubject()
      )
    );
  }
}
