<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 11/04/2018
 * Time: 20:50
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'profile.email',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Agency email address"
                )
            ))
            ->add('name', TextType::class, array(
                'label' => 'profile.name',
                'attr' => array(
                    'class' => 'input'
                )
            ))
            ->add('phone', TelType::class, array(
                'label' => 'profile.phone',
                'attr' => array(
                    'class' => 'input'
                )
            ))
            ->add('address', TextType::class, array(
                'label' => 'profile.address',
                'required' => false,
                'attr' => array(
                    'class' => 'input'
                )
            ))
            ->add('about', TextareaType::class, array(
                'label' => 'profile.about',
                'required' => false,
                'attr' => array(
                    'class' => 'textarea'
                )
            ))
            ->add('services', TextareaType::class, array(
                'label' => 'profile.services',
                'required' => false,
                'attr' => array(
                    'class' => 'textarea'
                )
            ))
            ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichFileType', array(
                'required' => false,
                'attr' => array(
                    'class' => 'file-input'
                )
            ))
            ->add('userStatus',ChoiceType::class,
                array(
                    'choices' => array(
                        'Hot customer' => true,
                        'All customer' => false,
                    ),


                ))
        ;
        $builder->remove('current_password');
        $builder->remove('username');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()

    {
        return 'app_user_profile';
    }

}