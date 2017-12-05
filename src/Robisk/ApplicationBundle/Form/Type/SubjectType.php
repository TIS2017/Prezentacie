<?php

namespace Robisk\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Translation\Translator;
use Robisk\ApplicationBundle\Entity\Subject;

class SubjectType extends AbstractType
{
    /** @var integer */
    private $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = new Translator('sk_SK');

        $builder
            ->add(
                'name',
                'text'
            )
            ->add(
                'schoolYear',
                'text'
            )
            ->add(
                'season',
                'choice',
                array(
                        'choices' => array(
                                Subject::SEASON_WINTER => $translator->trans('subject.season.winter'),
                                Subject::SEASON_SUMMER => $translator->trans('subject.season.summer')
                            ),
                        'empty_value' => $translator->trans('subject.season.label')
                    )
            )
            ->add(
                'userLimit',
                'text'
            )
            ->add(
                'weeks',
                'text'
            )
            ->add(
                'about',
                'textarea'
            );

        if ($this->action == 'edit') {
            $builder
                ->add(
                    'valPresentation',
                    'text'
                )
                ->add(
                    'valAttendance',
                    'text'
                );
        };

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'robisk_applicationbundle_subject';
    }
}