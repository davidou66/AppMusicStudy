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

/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 15:53
 */
class TaskType extends AbstractType
{
    public function __construct()
    {
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("content", TextType::class, array('attr'=> array('class' => 'form-control'), "label" => "Tâche", 'required' => true))

            ->add("save", SubmitType::class, array('label' => 'Ajouter'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'study_task';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'task';
    }
}
