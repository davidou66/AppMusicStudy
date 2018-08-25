<?php
namespace MusicStudyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;

/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 15:53
 */
class DocumentType extends AbstractType
{
    public function __construct()
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', VichFileType::class, array(
            'required'      => true,
            'allow_delete'  => true, // not mandatory, default is true
            'download_link' => true, // not mandatory, default is true
            'label'=>'Parcourir'
        ))
            ->add("fileName", TextType::class, array('attr'=> array('class' => 'form-control'), "label" => "Nom", 'required' => true))
            ->add('description', TextType::class, array('required' => false, 'attr' => array('class' => 'form-control', 'rows' => '3')))
            ->add('utilisateur', null, array('required' => false, 'placeholder' => 'Utilisateur ...', 'attr' => array('class' => 'form-control', 'rows' => '3')))
            ->add('type', ChoiceType::class, array('required' => true, 'placeholder' => 'Type ...',
                'choices'=>array(
                    'Cymbale' => 'Cymbale',
                    'Caisse claire' => 'Caisse claire',
                    'Grosse caisse' => 'Grosse caisse',
                    'Toms' => 'Toms',
                    'Charleston' => 'Charleston',
                    'Ambidextrie'=>'Ambidextrie',
                    'Solfège'=>'Solfège',
                    'Autre'=>'Autre'),
                "choices_as_values" => true,
                'attr' => array('class' => 'form-control', 'rows' => '3')))

            ->add("save", SubmitType::class, array('label' => 'Ajouter'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MusicStudyBundle\Entity\Document'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'study_user';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user';
    }
}
