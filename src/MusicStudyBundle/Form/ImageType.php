<?php
namespace MusicStudyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 15:53
 */
class ImageType extends AbstractType
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
            'allow_delete'  => false, // not mandatory, default is true
            'download_link' => false, // not mandatory, default is true
            'label'=> 'Parcourir'
        ))
        ->add('displayable', HiddenType::class, array(
            'data' => false
        ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MusicStudyBundle\Entity\Document',
            'needDescription' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'study_user_image';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user_image';
    }
}
