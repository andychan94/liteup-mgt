<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 11/04/2018
 * Time: 20:50
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Username',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Username"
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Agency email',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Agency email address"
                )
            ))
            ->add('name', TextType::class, array(
                'label' => 'Agency name',
                'attr' => array(
                    'class' => 'input'
                )
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Agency phone number',
                'attr' => array(
                    'class' => 'input'
                )
            ))
            ->add('address', TextType::class, array(
                'label' => 'Agency address',
                'attr' => array(
                    'class' => 'input'
                )
            ))
            ->add('about', TextareaType::class, array(
                'label' => 'About the agency',
                'required' => false,
                'attr' => array(
                    'class' => 'textarea'
                )
            ))
            ->add('services', TextareaType::class, array(
                'label' => 'Agency services',
                'required' => false,
                'attr' => array(
                    'class' => 'textarea'
                )
            ))
            ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichFileType', array(
                'label' => 'Agency logo',
                'required' => false,
                'attr' => array(
                    'class' => 'file-input'
                )
            ));
        $builder->remove('current_password');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()

    {
        return 'app_user_profile';
    }

    public function getName()

    {
        return $this->getBlockPrefix();
    }

}