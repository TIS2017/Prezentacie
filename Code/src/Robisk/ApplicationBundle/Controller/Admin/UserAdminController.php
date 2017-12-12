<?php

namespace Robisk\ApplicationBundle\Controller\Admin;

use Robisk\ApplicationBundle\Controller\BaseController;
use Robisk\ApplicationBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserAdminController extends BaseController
{
    public function indexAction()
    {
        $user = $this->getUser();
        $userManager = $this->get('manager_user');
        $users = $userManager->getRepository()->findAllUsers();

        return $this->render(
            'RobiskApplicationBundle:User/Admin:index.html.twig',
            array(
                'user' => $user,
                'users' => $users
            )
        );
    }

    public function resetPasswordAction($userId)
    {
        $userManager = $this->get('manager_user');

        /** @var User $user */
        $user = $userManager->find($userId);
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);

        $new_pwd_encoded = $encoder->encodePassword('1234567890', $user->getSalt());
        $user->setPassword($new_pwd_encoded);
        $userManager->update($user);

        $url = $this->generateUrl('route_admin_users');
        $response = new RedirectResponse($url);

        return $response;
    }

}