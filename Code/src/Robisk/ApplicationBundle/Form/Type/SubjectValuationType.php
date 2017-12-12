<?php

namespace Robisk\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Translation\Translator;
use Robisk\ApplicationBundle\Entity\Subject;

class SubjectValuationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'A',
                'text'
            )
            ->add(
                'B',
                'text'
            )
            ->add(
                'C',
                'text'
            )
            ->add(
                'D',
                'text'
            )
            ->add(
                'E',
                'text'
            )
            ->add(
                'Fx',
                'text'
            );
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'robisk_applicationbundle_subject_valuation';
    }
}