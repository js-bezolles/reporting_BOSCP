<?php
namespace AppBundle\Form;

use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BetweenDateSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('debutRcpt', 'date',array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'attr' => [
                'class' => 'form-control datepicker',
                'data-provide' => 'datepicker',
                'data-date-format' => 'dd-mm-yyyy'
            ],
            'required' => false
        ))
            ->add('finRcpt', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                ],
                'required' => false
            ))
            ->add('debutTrt', 'date',array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                ],
                'required' => false
            ))
            ->add('finTrt', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                ],
                'required' => false
            ))
            ->add('num_demande', 'text', array('label' => '# Demande :','attr' => ['style' => 'width:100%;', 'class' => 'form-control'],'required' => false))
            ->add('cod_etat_demande', 'text', array('label' => 'Etat Demande :', 'attr' => ['style' => 'width:100%;', 'class' => 'form-control'],'required' => false))
            ->add('code_origine', 'text', array('label' => 'Origine :', 'attr' => ['style' => 'width:100%;', 'class' => 'form-control'],'required' => false))
            ->add('ind_confirm_pro', 'text', array('label' => 'Confirm Pro :', 'attr' => ['style' => 'width:100%;', 'class' => 'form-control'],'required' => false))
            ->add('num_epj', 'text', array('label' => '# EPJ :','attr' => ['style' => 'width:100%;', 'class' => 'form-control'],'required' => false))
            ->add('nat_pro', 'text', array('label' => 'Nat Pro :', 'attr' => ['style' => 'width:100%;', 'class' => 'form-control'],'required' => false))
            ->add('date_reception', 'date',array(
                'label' => 'Date Reception :',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'style' => 'width:100%;',
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                ],
                'required' => false
            ))
            ->add('date_trt', 'date',array(
                'label' => 'Date Traitement :',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'style' => 'width:100%;',
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                ],
                'required' => false
            ))
            ->add('moderateur', 'text', array('label' => 'Modérateur', 'attr' => ['style' => 'width:100%;', 'class' => 'form-control'],'required' => false))
            ->add('im_label_dd', 'choice', array(
                'label' => 'Développement Durable :',
                'choices' => array(
                    '' => null,
                    'Oui' => 'O',
                    'Non' => 'N',
                ),
                'choices_as_values' => true,
                'attr' => ['style' => 'width:100%;', 'class' => 'form-control']
//                'required' => false
            ))
            ->add('type_jour_reception', 'choice', array(
                'label' => 'Jour de Réception',
                'choices' => array(
                    'Lundi' => 2,
                    'Mardi' => 3,
                    'Mercredi' => 4,
                    'Jeudi' => 5,
                    'Vendredi' => 6,
                    'Samedi' => 7,
                    'Dimanche' => 8
                ),
                'choices_as_values' => true,
                'expanded' => true,
                'multiple' => true,
                'attr' => ['style' => 'width:100%;height:auto;', 'class' => 'form-control'],'required' => false))
            ->add('type_jour_traitement', 'choice', array(
                'label' => 'Jour de Traitement',
                'choices' => array(
                    'Lundi' => 2,
                    'Mardi' => 3,
                    'Mercredi' => 4,
                    'Jeudi' => 5,
                    'Vendredi' => 6,
                    'Samedi' => 7,
                    'Dimanche' => 8
                ),
                'choices_as_values' => true,
                'expanded' => true,
                'multiple' => true,
                'attr' => ['style' => 'width:100%;height:auto;', 'class' => 'form-control'],'required' => false));

    }

    public function getName()
    {
        return 'AppBundle\Form\BetweenDateSearchType';
    }
}