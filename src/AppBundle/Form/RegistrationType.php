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
            ->remove('username')
            ->add('name', TextType::class, array(
                'label' => 'Username',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "First Name"
                )
            ))
            ->add('lastName', TextType::class, array(
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Last Name"
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Email address"
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

            ->add('phone', TelType::class, array(
                'label' => 'Phone number',
                'attr' => array(
                    'class' => 'input',
                    'placeholder' => "Phone number"
                )
            ))
            ->add('userType', ChoiceType::class, array(
                'choices' => array(
                    'User' => 'User',
                    'Agent' => 'Agent',
                    'Developer'   => 'Developer',
                    'Owner' => 'Owner',
                ),
                'expanded' => true,
                'choice_attr' => function($choiceValue, $key, $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => $key];
                },
            ))
        ;
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