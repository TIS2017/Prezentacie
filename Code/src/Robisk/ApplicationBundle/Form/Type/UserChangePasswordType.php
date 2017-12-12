<?php

namespace Robisk\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', 'password')
            ->add('user', new PasswordType());
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false
        );
    }

    public function getName()
    {
        return 'robisk_applicationbundle_user_change_password';
    }
}