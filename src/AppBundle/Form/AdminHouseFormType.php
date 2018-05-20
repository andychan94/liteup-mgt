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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
                'label' => 'lga.name',
                'group_by' => 'state',
                'choice_attr' => array(
                    'class' => 'someClass',
                )
            ))
            ->add('area', EntityType::class, array(
                'class' => 'AppBundle\Entity\Area',
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('u')->orderBy('u.name', 'ASC');
                },
                'required' => false,
                'label' => 'area.name',
                'group_by' => 'lga',
                'choice_attr' => array(
                    'class' => 'someClass'
                )
            ))
            ->add('address', TextType::class, array(
                'label' => 'house.form.address',
                'attr'=> array('class'=>'input')
            ))
            ->add('isShort', CheckboxType::class, array(
                'required' => false,
                'label' => 'house.form.isShort',
                'attr' => array('class' => 'is-checkradio is-block is-info')
            ))
            ->add('isRent', CheckboxType::class, array(
                'required' => false,
                'label' => 'house.form.isRent',
                'attr' => array('class' => 'is-checkradio is-block is-info')
            ))
            ->add('isBuy', CheckboxType::class, array(
                'required' => false,
                'label' => 'house.form.isBuy',
                'attr' => array('class' => 'is-checkradio is-block is-info')
            ))
            ->add('priceShort', HiddenType::class, array(
                'label' => 'house.form.priceShort',
                'required' => false
            ))
            ->add('priceRent', HiddenType::class, array(
                'label' => 'house.form.priceRent',
                'required' => false
            ))
            ->add('priceBuy', HiddenType::class, array(
                'label' => 'house.form.priceBuy',
                'required' => false
            ))
            ->add('title', TextType::class, array(
                'label' => 'house.form.title',
                'attr'=> array('class'=>'input')
            ))
            ->add('kind', ChoiceType::class, array(
                'label' => 'house.form.kind',
                'required' => true,
                'choices' => HouseKindEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseKindEnum::getTypeName($choice);
                },
            ))
            ->add('bedrooms', ChoiceType::class, array(
                'label' => 'house.form.bedrooms',
                'required' => true,
                'choices' => HouseBedroomsEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseBedroomsEnum::getTypeName($choice);
                },
            ))
            ->add('bathrooms', ChoiceType::class, array(
                'label' => 'house.form.bathrooms',
                'required' => true,
                'choices' => HouseBathroomsEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseBathroomsEnum::getTypeName($choice);
                },
            ))
            ->add('toilets', ChoiceType::class, array(
                'label' => 'house.form.toilets',
                'required' => true,
                'choices' => HouseBathroomsEnum::getAvailableTypes(),
                'choice_label' => function($choice) {
                    return HouseBathroomsEnum::getTypeName($choice);
                },
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'house.form.description',
                'required' => true,
                'attr'=> array('class'=>'textarea')
            ))
            ->add('features', FeatureType::class, array(
                'label' => 'house.form.features',
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