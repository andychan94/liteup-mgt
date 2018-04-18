<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 11/04/2018
 * Time: 20:50
 */

namespace AppBundle\Form;


use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminHouseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Title',
            ))
            ->add('price', TextType::class, array(
                'label' => 'Price',
            ))
            ->add('address', TextType::class, array(
                'label' => 'Address',
            ))
            ->add('size', TextType::class, array(
                'label' => 'Size',
            ))
            ->add('commute', TextType::class, array(
                'label' => 'Address',
            ))
            ->add('essentials', TextType::class, array(
                'label' => 'Address',
            ))
            ->add('type', TextType::class, array(
                'label' => 'Type',
            ))
            ->add('balcony_size', TextType::class, array(
                'label' => 'Balcony size',
            ))
            ->add('available', CheckboxType::class, array(
                'label' => 'Available',
            ))
            ->add('parking', CheckboxType::class, array(
                'label' => 'Parking',
            ))
            ->add('gas', CheckboxType::class, array(
                'label' => 'Gas',
            ))
            ->add('water', CheckboxType::class, array(
                'label' => 'Water',
            ))
            ->add('aircon', CheckboxType::class, array(
                'label' => 'Aircon',
            ))
        ;
    }

    public function getBlockPrefix()

    {
        return 'house_registration';
    }

    public function getName()

    {
        return $this->getBlockPrefix();
    }

}