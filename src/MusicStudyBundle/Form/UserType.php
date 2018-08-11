<?php
namespace MusicStudyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Created by PhpStorm.
 * User: David
 * Date: 17/12/2016
 * Time: 15:53
 */
class UserType extends AbstractType
{
    public function __construct()
    {
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("username", null, array('attr' => array('class' => 'form-control', 'onkeyup'=>'checkField("username", "#wrap_employe_employe_username")'), 'label' => 'Nom d\'utilisateur'))
            ->add("plainPassword", TextType::class, array(
                'required' => false,
                'attr'=> array('class' => 'form-control'), 'label' => 'Mot de passe'
            ))
            ->add("nom", TextType::class, array('attr'=> array('class' => 'form-control'), "label" => "Nom", 'required' => false))
            ->add("prenom", TextType::class, array('attr'=> array('class' => 'form-control'), 'label' => 'Prénom', 'required' => false))
            ->add("eMail", TextType::class, array('attr'=> array('class' => 'form-control', 'onkeyup'=>'checkField("email", "#wrap_employe_employe_eMail")'), 'label' => 'Email perso', 'required' => true))
            ->add("NumTelPort", TextType::class, array('label' => 'Numéro port.', 'attr' => array('class' => 'form-control'), 'required' => false))
            ->add('commentaire', null, array('required' => false, 'attr' => array('class' => 'form-control', 'rows' => '3')))
            ->add('sexe', ChoiceType::class, array(
                'choices' => array(
                    'Homme' => 'Homme',
                    'Femme' => 'Femme'
                ), "choices_as_values" => true, 'attr' => array('class' => 'required'), "label" => "Sexe",
            ))
            ->add('color', ChoiceType::class, array(
                'choices' => array(
                    'Rouge' => 'red',
                    'Bleu' => 'light-blue',
                    'Bleu ciel' => 'aqua',
                    'Jaune' => 'yellow',
                    'Vert' => 'green',
                    'Blanc' => 'white'
                ), "choices_as_values" => true, 'attr' => array('class' => 'required'), "label" => "Couleur",
            ))
//            ->add('avatar', DocumentType::class, [
//                'required' => false,
//                'allow_delete' => true, // not mandatory, default is true
//                'download_link' => false, // not mandatory, default is true
//                'label' => "Avatar"
//            ])
            ->add('avatar', ImageType::class, [
                'required' => false,
                'needDescription' => false
            ])
//            ->add('imageFile', VichImageType::class, [
//                'required' => false,
////                'needDescription' => false
//            ])
            ->add("save", SubmitType::class, array('label' => 'Enregistrer'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MusicStudyBundle\Entity\Utilisateur',
            'mdpRequired' => true
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
