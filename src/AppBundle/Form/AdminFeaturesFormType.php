<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 11/04/2018
 * Time: 20:50
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminFeaturesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'feature.form.name',
                'attr'=> array('class'=>'input')
            ))
        ;
    }

    public function getBlockPrefix()

    {
        return 'features_registration';
    }

}