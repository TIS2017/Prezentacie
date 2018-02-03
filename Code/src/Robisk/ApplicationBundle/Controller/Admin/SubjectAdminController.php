<?php

namespace Robisk\ApplicationBundle\Controller\Admin;

use Robisk\ApplicationBundle\Controller\BaseController;
use Robisk\ApplicationBundle\Entity\SubjectValuation;
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
use Robisk\ApplicationBundle\Form\Type\CommentType;

class SubjectAdminController extends BaseController {

	public function indexAction()
	{
		$user = $this->getUser();
		$subjectManager = $this->get('manager_subject');
		$subjects = $subjectManager->findAll();

		return $this->render(
            'RobiskApplicationBundle:Subject/Admin:index.html.twig',
            array(
   					'user'     => $user,
   					'subjects' => $subjects
            )
        );
	}

	public function addAction()
	{
		$request = $this->get('request');
		$subjectManager = $this->get('manager_subject');
		$svManager = $this->get('manager_subject_valuation');
		$user = $this->getUser();
		$subject = $subjectManager->create();
		$form = $this->createForm(new SubjectType('add'), $subject);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isValid()) {

				$subjectValuation = $svManager->create();
                $subjectValuation->setSubject($subject);
				$subjectManager->update($subject);
                $svManager->update($subjectValuation);
				$url = $this->generateUrl('route_admin_subject_edit', array('id' => $subject->getId()));

				$response = new RedirectResponse($url);
				return $response;
			}
		}

		$url = $this->generateUrl('route_admin_subject_add');

		return $this->render(
            'RobiskApplicationBundle:Subject/Admin:add.html.twig',
            array(
   					'action'  => 'add',
   					'form'    => $form->createView(),
   					'subject' => $subject,
   					'url'     => $url,
   					'user'    => $user,
            )
        );
	}

	public function editAction($id)
	{
		$request = $this->get('request');
		$subjectManager = $this->get('manager_subject');
		$user = $this->getUser();
		$subject = $subjectManager->findOneBy(array('id' => $id));
		$form = $this->createForm(new SubjectType('edit'), $subject);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isValid()) {

				$subjectManager->update($subject);
			}
		}

		$url = $this->generateUrl('route_admin_subject_edit', array('id' => $id));

		return $this->render(
            'RobiskApplicationBundle:Subject/Admin:edit.html.twig',
            array(
   					'action'  => 'edit',
   					'form'    => $form->createView(),
   					'subject' => $subject,
   					'url'     => $url,
   					'user'    => $user,
            )
        );
	}

	public function approveUserAction($id, $userId)
	{
		$uslManager = $this->get('manager_user_subject_lookup');
		$usl = $uslManager->findOneBy(array(
				'subject' => $id,
				'user'    => $userId
			));

		$usl->setStatus(UserSubjectLookup::STATUS_APPROVED);
		$uslManager->update($usl);

		$url = $this->generateUrl('route_admin_subject_edit', array('id' => $id));

		return $this->redirect($url);
	}

	public function approveAllUsersAction($id)
	{
		$subjectManager = $this->get('manager_subject');
		$uslManager = $this->get('manager_user_subject_lookup');
		$subject = $subjectManager->findOneBy(array('id' => $id));

		foreach($subject->getUnapprovedUsers() as $user) {
			$usl = $uslManager->findOneBy(array(
					'subject' => $id,
					'user'    => $user->getId()
				));

			$usl->setStatus(UserSubjectLookup::STATUS_APPROVED);
			$uslManager->update($usl);
		}

		$url = $this->generateUrl('route_admin_subject_edit', array('id' => $id));

		return $this->redirect($url);
	}

	public function declineUserAction($id, $userId)
	{
		$uslManager = $this->get('manager_user_subject_lookup');
		$usl = $uslManager->findOneBy(array(
				'subject' => $id,
				'user'    => $userId
			));
		$usl->setStatus(UserSubjectLookup::STATUS_DECLINED);
		$uslManager->update($usl);

		$url = $this->generateUrl('route_admin_subject_edit', array('id' => $id));

		return $this->redirect($url);
	}

	public function addAttendanceAction($id)
	{
		$request = $this->get('request');
		$subjectManager = $this->get('manager_subject');
		$attendanceManager = $this->get('manager_attendance');
		$subject = $subjectManager->findOneBy(array('id' => $id));
		$user = $this->getUser();

		$form = $this->createForm(new AttendanceType(), null, array('csrf_protection' => false));

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isValid()) {

                $attendance = $attendanceManager->create();
				$attendance
                    ->setSubject($subject)
                    ->setDate(new \DateTime($form->get('date')->getData()))
                    ->setPassword($form->get('password')->getData());
				$attendanceManager->update($attendance);

				$url = $this->generateUrl('route_admin_subject_edit', array('id' => $id));

				$response = new RedirectResponse($url);
				return $response;
			}
		}

		$url = $this->generateUrl('route_admin_subject_attendance_add', array('id' => $id));

		return $this->render(
            'RobiskApplicationBundle:Subject/Admin:addAttendance.html.twig',
            array(
   					'subject' => $subject,
   					'user'    => $user,
   					'form'    => $form->createView(),
   					'url'     => $url
            )
        );
	}

	public function lockAttendanceAction($id)
	{
		$attendanceManager = $this->get('manager_attendance');
		$subjectManager = $this->get('manager_subject');
		$subject = $subjectManager->findOneBy(array('id' => $id));
		$user = $this->getUser();
		$attendance = $subject->getUnlockedAttendance();

		$attendance->setStatus(Attendance::STATUS_LOCKED);
		$attendanceManager->update($attendance);

		$url = $this->generateUrl('route_admin_subject_edit', array('id' => $id));

		$response = new RedirectResponse($url);
		return $response;
	}

	public function viewStudentsAttendanceAction($id)
	{
		$request = $this->get('request');
		$subjectManager = $this->get('manager_subject');
		$ualManager = $this->get('manager_user_attendance_lookup');
		$subject = $subjectManager->findOneBy(array('id' => $id));
		$user = $this->getUser();
		$students = $subject->getApprovedUsers();

		if ($request->isMethod("POST")) {
			foreach($subject->getAttendances() as $attendance) {
				foreach($students as $student) {
					$ual = $ualManager->findOneBy(array(
							'user'       => $student,
							'attendance' => $attendance
						));
					if ($_POST['att_'. $attendance->getId() .'_'. $student->getId()] == '1') {
						if ($ual == null) {
							$ual = $ualManager->create();
							$ual->setUser($student);
							$ual->setAttendance($attendance);
							$ualManager->update($ual);
						}
					} else {
						if ($ual != null) {
							$ualManager->delete($ual);
						}
					}
				}
			}
		}

		$url = $this->generateUrl('route_admin_subject_attendance_students', array('id' => $id));

		return $this->render(
            'RobiskApplicationBundle:Subject/Admin:viewAttendance.html.twig',
            array(
   					'subject'  => $subject,
   					'user'     => $user,
   					'students' => $students,
   					'url'      => $url
            )
        );
	}

	public function userValuationAction($id, $userId)
	{
		$subjectManager = $this->get('manager_subject');
		$userManager = $this->get('manager_user');
		$uslManager = $this->get('manager_user_subject_lookup');
		$user = $this->getUser();

		$subject = $subjectManager->findOneBy(array('id' => $id));
		$student = $userManager->findOneBy(array('id' => $userId));
		$usl = $uslManager->findOneBy(array(
				'user'    => $student,
				'subject' => $subject
			));

		return $this->render(
            'RobiskApplicationBundle:Subject/Admin:userValuation.html.twig',
            array(
   					'subject' => $subject,
   					'student' => $student,
   					'user'    => $user,
   					'usl'     => $usl
            )
        );
	}

	public function settingsAction($id)
	{
		$request = $this->get('request');
		$subjectManager = $this->get('manager_subject');
		$userManager = $this->get('manager_user');
		$svManager = $this->get('manager_subject_valuation');
		$user = $this->getUser();
		$subject = $subjectManager->findOneBy(array('id' => $id));
		$subjectValuation = is_null($subject->getValuation()) ? new SubjectValuation() : $subject->getValuation();

		$form = $this->createForm(new SubjectValuationType, $subjectValuation, array('csrf_protection' => false));
		$pvpForms = array();
		$count = 0;
		foreach($subject->getPresentationValuationPoints() as $pvp) {
			$pvpForms[$count]['form'] = $this->createForm(new PresentationValuationPointType, $pvp, array('csrf_protection' => false))->createView();
			$pvpForms[$count]['id'] = $pvp->getId();
			$count++;
		}

		if ($request->isMethod("POST")) {
			$form->handleRequest($request);
			if ($form->isValid()) {

                $subjectValuation->setSubject($subject);
				$svManager->update($subjectValuation);
			}
		}

		$url = $this->generateUrl('route_admin_subject_settings', array('id' => $id));
		$pvpUrl = $this->generateUrl('route_admin_subject_presentation_valuation_point_save', array('id' => $id));

		return $this->render(
            'RobiskApplicationBundle:Subject/Admin:settings.html.twig',
            array(
   					'subject'  => $subject,
   					'user'     => $user,
   					'form'     => $form->createView(),
   					'url'      => $url,
   					'pvpUrl'   => $pvpUrl,
   					'pvpForms' => $pvpForms
            )
        );
	}

	public function changeStatusAction($id)
	{
		$subjectManager = $this->get('manager_subject');
		$subject = $subjectManager->findOneBy(array('id' => $id));

		if ($subject->getStatus() == Subject::STATUS_ACTIVE) {
			$subject->setStatus(Subject::STATUS_INACTIVE);
		} else {
			$subject->setStatus(Subject::STATUS_ACTIVE);
		}
		$subjectManager->update($subject);

		$url = $this->generateUrl('route_admin_subject_settings', array('id' => $id));

		$response = new RedirectResponse($url);
		return $response;
	}

	public function userPresentationAction($id, $userId, $presentationId)
	{
		$subjectManager = $this->get('manager_subject');
		$userManager = $this->get('manager_user');
		$commentManager = $this->get('manager_user_presentation_comment');
		$presentationManager = $this->get('manager_presentation');

		$request = $this->get('request');
		$presentation = $presentationManager->findOneBy(array('id' => $presentationId));
		$subject = $subjectManager->findOneBy(array('id' => $id));
		$student = $userManager->findOneBy(array('id' => $userId));
        $user = $this->getUser();

        $comment = $commentManager->create();

        $form = $this->createForm(new CommentType(), $comment, array('csrf_protection' => false));

        $url = $this->generateUrl('route_admin_subject_user_presentation',
            array(
                'id'             => $id,
                'userId'         => $userId,
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
            'RobiskApplicationBundle:Subject/Admin:userPresentation.html.twig',
            array(
   					'subject' => $subject,
   					'student' => $student,
   					'user'    => $user,
                    'presentation' => $presentation,
                    'form' => $form->createView(),
                    'url'  => $url
            )
        );
	}

    public function userPresentationDeleteCommentAction($id, $userId, $presentationId, $commentId){
        $commentManager = $this->get('manager_user_presentation_comment');
        $comment = $commentManager->findOneBy(array('id' => $commentId));
        $commentManager->delete($comment);

        $url = $this->generateUrl('route_admin_subject_user_presentation',
            array(
                'id'             => $id,
                'userId'         => $userId,
                'presentationId' => $presentationId
            ));
        $response = new RedirectResponse($url);
        return $response;
    }

	public function presentationDownloadAction($id, $userId)
    {
        $userManager = $this->get('manager_user');
        $student = $userManager->findOneBy(array('id' => $userId));
        $presentation = $student->getPresentation($id);

        $path = $presentation->getAbsolutePath();
        $content = file_get_contents($path);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$presentation->getPath());

        $response->setContent($content);
        return $response;
    }

    public function setPresentationStatusAction($id, $userId, $status)
    {
    	$presentationManager = $this->get('manager_presentation');
    	$userManager = $this->get('manager_user');
    	$subjectManager = $this->get('manager_subject');
    	$uslManager = $this->get('manager_user_subject_lookup');

    	$user = $userManager->findOneBy(array('id' => $userId));
    	$subject = $subjectManager->findOneBy(array('id' => $id));
    	$usl = $uslManager->findOneBy(array(
    			'user'    => $user,
    			'subject' => $subject
    		));
    	$presentation = $usl->getPresentation();

    	$presentation->setStatus($status);
    	$presentationManager->update($presentation);

    	$url = $this->generateUrl('route_admin_subject_user_presentation', array(
    			'id'     => $id,
    			'userId' => $userId
    		));

		$response = new RedirectResponse($url);
		return $response;
    }

    public function addPresentationValuationPointAction($id)
    {
    	$subjectManager = $this->get('manager_subject');
    	$pvpManager = $this->get('manager_presentation_valuation_point');
    	$subject = $subjectManager->findOneBy(array('id' => $id));

    	$pvp = $pvpManager->create();
    	$pvp->setSubject($subject);
    	$pvpManager->update($pvp);

    	$url = $this->generateUrl('route_admin_subject_settings', array('id' => $id));

		$response = new RedirectResponse($url);
		return $response;
    }

    public function savePresentationValuationPointAction($id)
    {
    	$request = $this->get('request');
    	$pvpManager = $this->get('manager_presentation_valuation_point');
    	$subjectManager = $this->get('manager_subject');
    	$subject = $subjectManager->findOneBy(array('id' => $id));

    	if ($request->isMethod("POST")) {
    		foreach($subject->getPresentationValuationPoints() as $pvp) {
    			$pvp->setPoint($_POST['pvp_point_'. $pvp->getId()]);
    			$pvp->setHeight($_POST['pvp_height_'. $pvp->getId()]);

				$pvpManager->update($pvp);
			}
    	}

    	$url = $this->generateUrl('route_admin_subject_settings', array('id' => $id));

		$response = new RedirectResponse($url);
		return $response;
    }

    public function deletePresentationValuationPointAction($id, $pvpId)
    {
    	$pvpManager = $this->get('manager_presentation_valuation_point');
    	$pvp = $pvpManager->findOneBy(array('id' => $pvpId));

    	$pvpManager->delete($pvp);

    	$url = $this->generateUrl('route_admin_subject_settings', array('id' => $id));

		$response = new RedirectResponse($url);
		return $response;
    }

    public function sendMailAction($id){
				$request = $this->get('request');
	    	$subjectManager= $this->get('manager_subject');
        $subject = $subjectManager->findOneBy(array('id' => $id));

			  $form = $this->createForm(new SendMailType(), array('csrf_protection' => false));

				if ($request->isMethod("POST")) {
							$data = $request->request->all();
							$data2 = $data['robisk_applicationbundle_sendMail'];
							$from = "From: rybar@uniba.sk \r\n CC: ";
							$predmet = $data2['subject'];
							$sprava = $data2['content'];
							$sprava = str_replace("\n", "<br>", $sprava);

							foreach($data as $i){
								if($i!=$data2){
										if ($i!='null'){
											$to = $i;
											//var_dump($to, $predmet, $sprava, $from);
											$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')->setUsername('kognitivnevedy')->setPassword('lnboegklgdxoyrlf');
											$mailer = \Swift_Mailer::newInstance($transport);
											$message = \Swift_Message::newInstance($predmet)
											   ->setFrom(array('kognitivnevedy@gmail.com' => $predmet))
											   ->setTo(array($to => $to))
											   ->setBody($sprava . "<br><br><strong>Prosím neodpisujte na tento email.</strong><br><br>", 'text/html');
											$result = $mailer->send($message);
									}
								}
							}
						}


	    return $this->render('RobiskApplicationBundle:Subject/Admin:sendMail.html.twig', array('subject' => $subject, 'form' => $form->createView()));
    }

}
