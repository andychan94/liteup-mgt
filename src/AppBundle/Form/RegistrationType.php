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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
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
                'label' => 'Email',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Agency email address"
                )
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array(
                    'label' => 'Password',
                    'attr' => array(
                        'class' => 'input',
                        'placeholder' => "Password"
                    )
                ),
                'second_options' => array(
                    'label' => 'Repeat password',
                    'attr' => array(
                        'class' => 'input',
                        'placeholder' => "Repeat password"
                    )
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('name', TextType::class, array(
                'label' => 'Agency name',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Agency name"
                )
            ))
            ->add('phone', TelType::class, array(
                'label' => 'Phone number',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Agency phone number"
                )
            ))
            ->add('address', TextType::class, array(
                'label' => 'Address',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Agency address"
                )
            ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()

    {
        return 'app_user_registration';
    }

}