<?php

namespace Robisk\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Robisk\ApplicationBundle\Form\Type\PasswordResetType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Robisk\ApplicationBundle\Form\Type\PasswordType;
use Robisk\ApplicationBundle\Entity\User;

class SecurityController extends BaseController
{
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'RobiskApplicationBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    public function forgottenAction(){
      $request = $this->get('request');

      $form = $this->createForm(new PasswordResetType(), array('csrf_protection' => false));

      if ($request->isMethod("POST")) {
        $data = $request->request->all();
        $email = $data['robisk_applicationbundle_PasswordReset']['mail'];
        $userManager = $this->get("manager_user");
        $user_exists = $this->getDoctrine()->getRepository('RobiskApplicationBundle:User')->findOneBy(array('email'=>$email));
        if ($user_exists!=null){
          if($user_exists->getEmail()==$email){
            $userSalt = $user_exists->getSalt();

            $url = $this->generateUrl('reset_password',
                array(  'salt' => $userSalt,
                        ), true);
            //$response = new RedirectResponse($url);
            //return $response;

            $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')->setUsername('kognitivnevedy')->setPassword('lnboegklgdxoyrlf');
            $mailer = \Swift_Mailer::newInstance($transport);
            $message = \Swift_Message::newInstance("Reset hesla")
               ->setFrom(array('kognitivnevedy@gmail.com' => "Reset hesla"))
               ->setTo(array($email => $email))
               ->setBody("Pre reset hesla kliknite na tento <a href=" . $url . "><strong>LINK</strong></a>.", 'text/html');
            $result = $mailer->send($message);
          }
        }
      }
      return $this->render('RobiskApplicationBundle:Security:forgotten.html.twig', array('form' => $form->createView()));
    }

    public function resetPasswordAction(){
      $request = $this->get('request');

      $userManager = $this->get('manager_user');
      $tmp = $request->attributes->get('salt');
      var_dump($tmp);
      $user = $userManager->findOneBy(array('salt' => $request->attributes->get('salt')));
      //var_dump($user);
      $form = $this->createForm(new PasswordType(), $user);

      if ($request->isMethod("POST")) {
        $form->handleRequest($request);
        $data = $request->request->all();
        $data2 = $data['robisk_applicationbundle_password']['password'];
        $password1 = $data2['password'];
        $password2 = $data2['confirm'];

        if (strlen($password1)>=6){
          if ($password1==$password2){

            $encoderFactory = $this->get('security.encoder_factory');
            $encoder = $encoderFactory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());

            $user->setPassword($password);
            $userManager->update($user);

            return $this->render(
                'RobiskApplicationBundle:Security:login.html.twig',
                array(
                    // last username entered by the user
                    'last_username' => '',
                    'error'         => '',));
          }
        }
      }

      return $this->render('RobiskApplicationBundle:Security:reset.html.twig', array('form' => $form->createView()));
    }
}
