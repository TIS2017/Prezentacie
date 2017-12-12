<?php

namespace Robisk\ApplicationBundle\Controller;

use Robisk\ApplicationBundle\Form\Type\RegistrationType;
use Robisk\ApplicationBundle\Form\Type\ProfileType;
use Robisk\ApplicationBundle\Form\Type\UserChangePasswordType;
use Robisk\ApplicationBundle\Form\Model\Registration;
use Robisk\ApplicationBundle\Form\Model\ChangePassword;
use Robisk\ApplicationBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends BaseController
{
    public function registrationAction()
    {
        $request = $this->get('request');
        $userManager = $this->get('manager_user');
        $form = $this->createForm(new RegistrationType(), new Registration(), array('csrf_protection' => false));

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $registration = $form->getData();
                $user = $registration->getUser();

                //Handle encoding here...
                $encoderFactory = $this->get('security.encoder_factory');
                $encoder = $encoderFactory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                
                $user->setPassword($password);
                $userManager->update($user);
                $url = $this->generateUrl('route_home');

                return $this->redirect($url);
            }
        }

        $url = $this->generateUrl('route_registration');

        return $this->render(
            'RobiskApplicationBundle:User:registration.html.twig', 
            array(
                'form'     => $form->createView(),
                'url'      => $url,
                'ajax_url' => $url
            )
        );
    }

    public function addSubjectAction($id)
    {
        $subjectManager = $this->get('manager_subject');
        $uslManager = $this->get('manager_user_subject_lookup');
        $subject = $subjectManager->findOneBy(array('id' => $id));
        $user = $this->getUser();

        $usl = $uslManager->create();
        $usl->setUser($user);
        $usl->setSubject($subject);
        $uslManager->update($usl);

        $url = $this->generateUrl('route_subjects');

        $response = new RedirectResponse($url);
        return $response;
    }

    public function addAttendanceAction($id)
    {
        $request = $this->get('request');
        $ualManager = $this->get('manager_user_attendance_lookup');
        $attendanceManager = $this->get('manager_attendance');
        $user = $this->getUser();
        $attendance = $attendanceManager->findOneBy(array('id' => $id));

        if ($request->isMethod('POST')) {
            if ($attendance->getPassword() == md5($_POST['attendance_password'])) {
                $ual = $ualManager->create();
                $ual->setUser($user);
                $ual->setAttendance($attendance);
                $ualManager->update($ual);
            }
        }

        $url = $this->generateUrl('route_subject_view', array('id' => $attendance->getSubject()->getId()));

        $response = new RedirectResponse($url);
        return $response;
    }

    public function profileAction()
    {
        $request = $this->get('request');
        $userManager = $this->get('manager_user');
        $user = $this->getUser();
        $profileForm = $this->createForm(new ProfileType(), $user);

        $changePasswordModel = new ChangePassword();
        $passwordForm = $this->createForm(new UserChangePasswordType(), $changePasswordModel);

        if ($request->isMethod('POST')) {
            $profileForm->handleRequest($request);
            if ($profileForm->isValid()) {

                $userManager->update($user);
            }
        }

        $profileUrl = $this->generateUrl('route_user_profile');
        $passwordUrl = $this->generateUrl('route_user_password_change');

        return $this->render(
            'RobiskApplicationBundle:User:profile.html.twig', 
            array(
                'user'          => $user,
                'profile_form'  => $profileForm->createView(),
                'profile_url'   => $profileUrl,
                'password_form' => $passwordForm->createView(),
                'password_url'  => $passwordUrl
            )
        );
    }

    public function changePasswordAction()
    {
        $request = $this->get('request');
        $userManager = $this->get('manager_user');
        $changePasswordModel = new ChangePassword();
        $user = $this->getUser();
        $user_tmp = $this->getUserBlank();
        $changePasswordModel->setUser($user_tmp);
        $form = $this->createForm(new UserChangePasswordType(), $changePasswordModel, array('csrf_protection' => false));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                
                $changePassword = $form->getData();
                $user_tmp = $changePassword->getUser();

                //Handle encoding here...
                $encoderFactory = $this->get('security.encoder_factory');
                $encoder = $encoderFactory->getEncoder($user);
                $password = $encoder->encodePassword($user_tmp->getPassword(), $user->getSalt());
                    
                $user->setPassword($password);
                $userManager->update($user); 
            }
        }

        $url = $this->generateUrl('route_user_profile');    

        $response = new RedirectResponse($url);
        return $response;
    }

    private function getUserBlank()
    {
        $user = new User();
        $user->setFirstName('xx');
        $user->setLastName('xx');
        $user->setEmail('xx@xx.xx');
        $user->setUsername('xx');

        return $user;
    }

}