<?php

namespace Robisk\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AttendanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'date',
                'text'
            )
            ->add(
                'password',
                'text'
            );
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false
        );
    }

    public function getName()
    {
        return 'robisk_applicationbundle_attendance';
    }
}