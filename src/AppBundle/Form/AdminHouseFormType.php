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
use AppBundle\Enum\HouseBathroomsEnum;
use AppBundle\Enum\HouseBedroomsEnum;
use AppBundle\Enum\HouseKindEnum;
use AppBundle\Enum\HouseTypeEnum;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminHouseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('address', TextType::class, array(
                'label' => 'Street',
                'attr'=> array('class'=>'input')
            ))
            ->add('type', ChoiceType::class, array(
                'required' => true,
                'choices' => HouseTypeEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseTypeEnum::getTypeName($choice);
                },
            ))
            ->add('price', IntegerType::class, array(
                'label' => 'Price',
                'attr'=> array('class'=>'input')
            ))

            ->add('title', TextType::class, array(
                'label' => 'Title',
                'attr'=> array('class'=>'input')
            ))
            ->add('kind', ChoiceType::class, array(
                'required' => true,
                'choices' => HouseKindEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseKindEnum::getTypeName($choice);
                },
            ))
            ->add('bedrooms', ChoiceType::class, array(
                'required' => true,
                'choices' => HouseBedroomsEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseBedroomsEnum::getTypeName($choice);
                },
            ))
            ->add('bathrooms', ChoiceType::class, array(
                'required' => true,
                'choices' => HouseBathroomsEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseBathroomsEnum::getTypeName($choice);
                },
            ))
            ->add('toilets', ChoiceType::class, array(
                'required' => true,
                'choices' => HouseBathroomsEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseBathroomsEnum::getTypeName($choice);
                },
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr'=> array('class'=>'textarea')
            ))
            ->add('aircon', CheckboxType::class, array(
                'label' => 'Aircon',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
            ))
            ->add('balcony', CheckboxType::class, array(
                'label' => 'Aircon',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
            ))
            ->add('fence', CheckboxType::class, array(
                'label' => 'Aircon',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
            ))
            ->add('garage', CheckboxType::class, array(
                'label' => 'Aircon',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
            ))
            ->add('garden', CheckboxType::class, array(
                'label' => 'Parking',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
            ))
            ->add('swpool', CheckboxType::class, array(
                'label' => 'Water',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
            ))
            ->add('fountain', CheckboxType::class, array(
                'label' => 'Water',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
            ))
            ->add('parking', CheckboxType::class, array(
                'label' => 'Water',
                'required' => false,
                'attr'=> array('class'=>'switch is-rounded')
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