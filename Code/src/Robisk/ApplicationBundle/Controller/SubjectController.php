<?php

namespace Robisk\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Robisk\ApplicationBundle\Form\Type\PresentationType;
use Robisk\ApplicationBundle\Form\Type\CommentType;
use Robisk\ApplicationBundle\Form\Type\PresentationValuatingType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class SubjectController extends BaseController
{
    public function indexAction()
    {
        $subjectManager = $this->get('manager_subject');

        $subjects = $subjectManager->getRepository()->getActiveSubjects();
        $user = $this->getUser();

        $url = $this->generateUrl('route_subjects');

        return $this->render(
            'RobiskApplicationBundle:Subject:index.html.twig',
            array(
                'subjects' => $subjects,
                'user'     => $user,
                'url'      => $url,
            )
        );
    }

    public function viewAction($id)
    {
        $subjectManager = $this->get('manager_subject');
        $attendanceManager = $this->get('manager_attendance');
        $presentationManager = $this->get('manager_presentation');
        $uslManager = $this->get('manager_user_subject_lookup');
        $subject = $subjectManager->findOneBy(array('id' => $id));
        $unlockedAtt = $attendanceManager->getUnlockedAttendance($id);
        $user = $this->getUser();
        $usl = $uslManager->findOneBy(array(
                'user'    => $user,
                'subject' => $subject
            ));

        $addAttendanceUrl = '';
        if ($unlockedAtt != null) {
            $addAttendanceUrl = $this->generateUrl('route_subject_user_attendance_add', array('id' => $unlockedAtt->getId()));
        }

        $presentation = $presentationManager->create();
        $uploadForm = $this->createForm(new PresentationType(), $presentation, array('csrf_protection' => false));
        $uploadUrl = $this->generateUrl('route_subject_presentation_upload', array('id' => $id));

        $presentationAddUserUrl = '';
        if ($user->getPresentation($id) != null) {
            $presentationAddUserUrl = $this->generateUrl('route_subject_presentation_add_user', array(
                    'id'             => $id,
                    'presentationId' => $user->getPresentation($id)->getId()
                ));
        }

        return $this->render(
            'RobiskApplicationBundle:Subject:view.html.twig',
            array(
                'subject'                => $subject,
                'user'                   => $user,
                'unlockedAtt'            => $unlockedAtt,
                'addAttendanceUrl'       => $addAttendanceUrl,
                'uploadForm'             => $uploadForm->createView(),
                'uploadUrl'              => $uploadUrl,
                'presentationAddUserUrl' => $presentationAddUserUrl,
                'usl'                    => $usl
            )
        );
    }

    public function announcementsAction($id)
    {
        $subjectManager = $this->get('manager_subject');
        $announcementManager = $this->get('manager_announcement');
        $userManager = $this->get('manager_user');
        $subject = $subjectManager->findOneBy(array('id' => $id));
        $announcements = $announcementManager->findBy(array('subject' => $subject));
        $user = $this->getUser();

        $user->setLastVisitedAnnouncements(new \DateTime("now"));
        $userManager->update($user);

        return $this->render(
            'RobiskApplicationBundle:Announcement:index.html.twig',
            array(
                'subject'       => $subject,
                'user'          => $user,
                'announcements' => $announcements,
            )
        );
    }

    public function presentationUploadAction($id)
    {
        $request = $this->get('request');
        $presentationManager = $this->get('manager_presentation');
        $uslManager = $this->get('manager_user_subject_lookup');
        $subjectManager = $this->get('manager_subject');
        $user = $this->getUser();
        $presentation = $presentationManager->create();
        $uploadForm = $this->createForm(new PresentationType(), $presentation, array('csrf_protection' => false));

        if ($request->isMethod('POST')) {
            $uploadForm->handleRequest($request);
            if ($uploadForm->isValid()) {

                $subject = $subjectManager->findOneBy(array('id' => $id));
                $usl = $uslManager->findOneBy(array(
                        'user'    => $user,
                        'subject' => $subject
                    ));
                $presentation->addUserSubjectLookup($usl);
                $presentation->setPath($presentation->getFile()->getClientOriginalName());
                $presentation->setOwner($user);
                $presentationManager->update($presentation);
                $presentation->upload();

                $usl->setPresentation($presentation);
                $uslManager->update($usl);
            }
        }

        $url = $this->generateUrl('route_subject_view', array('id' => $id));

        $response = new RedirectResponse($url);
        return $response;
    }

    public function presentationDownloadAction($id, $presentationId)
    {
        $presentationManager = $this->get('manager_presentation');
        $presentation = $presentationManager->findOneBy(array('id' => $presentationId));

        $path = $presentation->getAbsolutePath();
        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, iconv("UTF-8", "ASCII", $presentation->getTitle()));

        return $response;
    }

    public function presentationDeleteAction($id, $presentationId)
    {
        $uslManager = $this->get('manager_user_subject_lookup');
        $presentationManager = $this->get('manager_presentation');

        $presentation = $presentationManager->findOneBy(array('id' => $presentationId));

        $usls = $uslManager->findBy(array(
                'presentation' => $presentation,
            ));

        foreach($usls as $usl) {
            $usl->setPresentation();
            $uslManager->update($usl);
        }
        $presentationManager->delete($presentation);

        $url = $this->generateUrl('route_subject_view', array('id' => $id));

        $response = new RedirectResponse($url);
        return $response;
    }

    public function presentationAddUserAction($id, $presentationId)
    {
        $request = $this->get('request');
        $presentationManager = $this->get('manager_presentation');
        $uslManager = $this->get('manager_user_subject_lookup');
        $subjectManager = $this->get('manager_subject');
        $userManager = $this->get('manager_user');

        $subject = $subjectManager->findOneBy(array('id' => $id));
        $presentation = $presentationManager->findOneBy(array('id' => $presentationId));

        if ($request->isMethod('POST')) {

            $user = $userManager->findOneBy(array('id' => $_POST['presentation_user_add']));
            if ($user != null) {
                $usl = $uslManager->findOneBy(array(
                        'user'    => $user,
                        'subject' => $subject
                    ));
                $usl->setPresentation($presentation);

                $uslManager->update($usl);
            }
        }

        $url = $this->generateUrl('route_subject_view', array('id' => $id));

        $response = new RedirectResponse($url);
        return $response;
    }

    public function presentationDeleteUserAction($id, $presentationId, $userId)
    {
        $uslManager = $this->get('manager_user_subject_lookup');
        $subjectManager = $this->get('manager_subject');
        $userManager = $this->get('manager_user');
        $presentationManager = $this->get('manager_presentation');

        $user = $userManager->findOneBy(array('id' => $userId));
        $subject = $subjectManager->findOneBy(array('id' => $id));

        $usl = $uslManager->findOneBy(array(
                'user'    => $user,
                'subject' => $subject
            ));

        $usl->setPresentation();
        $uslManager->update($usl);

        $url = $this->generateUrl('route_subject_view', array('id' => $id));

        $response = new RedirectResponse($url);
        return $response;
    }

    public function presentationValuatingAction($id, $userId)
    {
        $request = $this->get('request');
        $subjectManager = $this->get('manager_subject');
        $userManager = $this->get('manager_user');
        $uslManager = $this->get('manager_user_subject_lookup');
        $upvManager = $this->get('manager_user_presentation_valuation');
        $user = $this->getUser();
        $subject = $subjectManager->findOneBy(array('id' => $id));
        $student = $userManager->findOneBy(array('id' => $userId));
        $usl = $uslManager->findOneBy(array(
                'user'    => $student,
                'subject' => $subject
            ));
        $studentUsl = $uslManager->findOneBy(array(
                'user'    => $user,
                'subject' => $subject
            ));
        $presentation = $usl->getPresentation();

        if ($request->isMethod("POST")) {
            foreach($subject->getPresentationValuationPoints() as $pvp) {
                $upv = $upvManager->findOneBy(array(
                        'whoseUsl'                   => $studentUsl,
                        'targetUsl'                  => $usl,
                        'presentationValuationPoint' => $pvp
                    ));
                if ($upv == null) {
                    $val = $_POST['points_'. $pvp->getId()];
                    if ($val > 10) {
                        $val = 10;
                    }
                    if ($val < 0) {
                        $val = 0;
                    }
                    $upv = $upvManager->create();
                    $upv->setWhoseUsl($studentUsl);
                    $upv->setTargetUsl($usl);
                    $upv->setPresentationValuationPoint($pvp);
                    $upv->setPoints($val);
                    $upvManager->update($upv);
                }
            }
        }

        $url = $this->generateUrl('route_subject_presentation_valuating', array(
                'id'     => $id,
                'userId' => $userId
            ));

        return $this->render(
            'RobiskApplicationBundle:Subject:presentationValuating.html.twig',
            array(
                'subject'      => $subject,
                'user'         => $user,
                'student'      => $student,
                'presentation' => $presentation,
                'url'          => $url,
                'usl'          => $studentUsl
            )
        );
    }

    public function teacherPresentationViewAction($id){
        $subjectManager = $this->get('manager_subject');

        $subject = $subjectManager->findOneBy(array('id' => $id));

        return $this->render(
            'RobiskApplicationBundle:Subject:teacherPresentationView.html.twig',
            array(
                'subject'      => $subject,
            )
        );
    }
}
