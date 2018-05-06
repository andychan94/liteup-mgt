<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 11/04/2018
 * Time: 20:50
 */

namespace AppBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminAreasFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'area.form.name',
                'attr' => array('class' => 'input')
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
    ));
    }

    public function getBlockPrefix()
    {
        return 'areas_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

}