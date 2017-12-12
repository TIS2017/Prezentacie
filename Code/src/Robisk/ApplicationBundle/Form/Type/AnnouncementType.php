<?php

namespace Robisk\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('content', 'textarea');
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