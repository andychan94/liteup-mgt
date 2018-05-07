<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 11/04/2018
 * Time: 20:50
 */

namespace AppBundle\Form;


use AppBundle\Entity\Area;
use AppBundle\Entity\Lga;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminHouseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Title',
                'attr'=> array('class'=>'input')
            ))
            ->add('price', IntegerType::class, array(
                'label' => 'Price',
                'attr'=> array('class'=>'input')
            ))
            ->add('address', TextType::class, array(
                'label' => 'Address',
                'attr'=> array('class'=>'input')
            ))
            ->add('size', TextType::class, array(
                'label' => 'Size',
                'attr'=> array('class'=>'input')
            ))
            ->add('lga', EntityType::class, array(
                'class' => 'AppBundle\Entity\Lga',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('u')->orderBy('u.name', 'ASC');
                },
                'label' => 'area.form.lga',
                'group_by' => 'state',
                'choice_attr' => array(
                    'class' => 'someClass'
                )
            ))
            ->add('area', EntityType::class, array(
                'class' => 'AppBundle\Entity\Area',
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('u')->orderBy('u.name', 'ASC');
                },
                'label' => 'area.form.lga',
                'group_by' => 'lga',
                'choice_attr' => array(
                    'class' => 'someClass'
                )
            ))
            ->add('essentials', TextType::class, array(
                'label' => 'Essentials',
                'required' => false,
                'attr'=> array('class'=>'input')
            ))
            ->add('commute', TextType::class, array(
                'label' => 'Commute',
                'required' => false,
                'attr'=> array('class'=>'input')
            ))
            ->add('type', TextType::class, array(
                'required' => false,
                'label' => 'Type',
                'attr'=> array('class'=>'input')
            ))
            ->add('balcony_size', TextType::class, array(
                'label' => 'Balcony size',
                'required' => false,
                'attr'=> array('class'=>'input')
            ))
            ->add('available', CheckboxType::class, array(
                'label' => 'Available',
                'required' => false,
                'attr'=> array('class'=>'checkbox')
            ))
            ->add('parking', CheckboxType::class, array(
                'label' => 'Parking',
                'required' => false,
                'attr'=> array('class'=>'checkbox')
            ))
            ->add('gas', CheckboxType::class, array(
                'label' => 'Gas',
                'required' => false,
                'attr'=> array('class'=>'checkbox')
            ))
            ->add('water', CheckboxType::class, array(
                'label' => 'Water',
                'required' => false,
                'attr'=> array('class'=>'checkbox')
            ))
            ->add('aircon', CheckboxType::class, array(
                'label' => 'Aircon',
                'required' => false,
                'attr'=> array('class'=>'checkbox')
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