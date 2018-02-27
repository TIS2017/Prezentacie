<?php

namespace Robisk\ApplicationBundle\Controller\Admin;

use Robisk\ApplicationBundle\Controller\BaseController;
use Robisk\ApplicationBundle\Entity\SubjectValuation;
use Robisk\ApplicationBundle\Form\Type\TeacherPresentationType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Robisk\ApplicationBundle\Form\Type\SubjectType;
use Robisk\ApplicationBundle\Form\Type\AttendanceType;
use Robisk\ApplicationBundle\Form\Type\SubjectValuationType;
use Robisk\ApplicationBundle\Form\Type\PresentationValuationPointType;
use Robisk\ApplicationBundle\Entity\UserSubjectLookup;
use Robisk\ApplicationBundle\Entity\Attendance;
use Robisk\ApplicationBundle\Entity\Subject;
use Robisk\ApplicationBundle\Form\Type\SendMailType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TeacherPresentationAdminController extends BaseController {

    public function IndexAction($id){
        $subjectManager = $this->get('manager_subject');

        $subject = $subjectManager->findOneBy(array('id' => $id));
        //$teacherPresentations = $subject->getTeacherPresentations();

        return $this->render('RobiskApplicationBundle:Subject/Admin:teacherPresentation.html.twig', array(
            'subject' => $subject));
    }

    public function addAction($id){
        $request = $this->get('request');
        $subjectManager = $this->get('manager_subject');
        $teacherPresentationManager = $this->get('manager_teacher_presentation');

        $subject = $subjectManager->findOneBy(array('id' => $id));
        $form = $this->createForm(new TeacherPresentationType(), null, array('csrf_protection' => false));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $teacherPres = $teacherPresentationManager->create();
                $teacherPres->setSubject($subject);

                $teacherPres->setDate(new \DateTime($form->get('date')->getData()));
                $teacherPres->setTitle($form->get('title')->getData());
                $teacherPres->setFile($form->get('file')->getData());

                $teacherPres->setPath($teacherPres->getFile()->getClientOriginalName());
                $teacherPresentationManager->update($teacherPres);
                $teacherPres->upload();

                //vyzera ze toto tu netreba for some reason $subject->addTeacherPresentation($teacherPres)

                $url = $this->generateUrl('route_teacher_presentation',
                    array(
                        'id'             => $id,
                    ));
                $response = new RedirectResponse($url);
                return $response;
            }
        }

        $url = $this->generateUrl('route_teacher_presentation_add', array('id' => $id));

        return $this->render('RobiskApplicationBundle:Subject/Admin:addTeacherPresentation.html.twig', array(
            'subject' => $subject,
            'form' => $form->createView(),
            'url' => $url));
    }

    public function downloadAction($id){
        $teacherPresentationManager = $this->get('manager_teacher_presentation');

        $presentation = $teacherPresentationManager->findOneBy(array('id' => $id));

        $path = $presentation->getAbsolutePath();
        $content = file_get_contents($path);
        $response = new BinaryFileResponse($path);

        return $response;
    }

    public function deleteAction($id){
        $teacherPresentationManager = $this->get('manager_teacher_presentation');
        $subjectManager = $this->get('manager_subject');

        $presentation = $teacherPresentationManager->findOneBy(array('id' => $id));
        $subject = $subjectManager->findOneBy(array('id' => $presentation->getSubject()->getId()));

        //toto asi netreba $subject->removeTeacherPresentation($presentation);
        $teacherPresentationManager->delete($presentation);

        $url = $this->generateUrl('route_teacher_presentation',
            array(
                'id'             => $subject->getId(),
            ));
        $response = new RedirectResponse($url);
        return $response;
    }
}
