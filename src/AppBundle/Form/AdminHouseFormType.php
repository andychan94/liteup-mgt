<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 11/04/2018
 * Time: 20:50
 */

namespace AppBundle\Form;


use AppBundle\Entity\Feature;
use AppBundle\Entity\Type;
use AppBundle\Enum\HouseBathroomsEnum;
use AppBundle\Enum\HouseBedroomsEnum;
use AppBundle\Enum\HouseKindEnum;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('lgaId', EntityType::class, array(
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
            ->add('types', TypeType::class, array(
                'required' => true,
                'class' => Type::class,
                'expanded' => true,
                'multiple' => true,
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
            ->add('features', FeatureType::class, array(
                'class' => Feature::class,
                'expanded' => true,
                'multiple' => true,
            ))
        ;
    }

    public function getBlockPrefix()

    {
        return 'house_registration';
    }

}