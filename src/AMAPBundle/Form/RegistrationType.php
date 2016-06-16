<?php 
namespace AMAPBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
        $builder->add('prenom');
        $builder->add('dateNaissance');
        $builder->add('typeActeur',EntityType::class,array('class' => 'AMAPBundle:TypeActeur', 'choice_label' => 'libelle'));
        $builder->add('amap',EntityType::class,array('class' => 'AMAPBundle:Amap', 'choice_label' => 'libelle'));
        $builder->add('adresse',AdresseType::class);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}