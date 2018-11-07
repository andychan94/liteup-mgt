<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/5/2018
 * Time: 12:47 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\PlanSubscription;
use AppBundle\Entity\PlanSubscriptionCondition;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PlanSubscriptionAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof PlanSubscription
            ? $object->getPlanTitle()
            : 'Plan';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('planOrder')
            ->add('planTitle')
            ->add('planPrice')
            ->add('planAnyMonth',CollectionType::class, array(
                'mapped' => true,
                'allow_add' => true,
                'entry_type' => TextType::class,
                'allow_delete' => true,
                'label' => 'Coins Amount',
                'label_attr' => array('class' => 'Coins Amount Text'),
            ))
            ->add('planCondition',EntityType::class,array(
                'class' => PlanSubscriptionCondition::class,
                'choice_label' => 'conditionTitle',
                'multiple' => true,
            ))
            ->add('planText',CKEditorType::class,['required' => false])
            ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('planOrder',null,['editable' =>true])
            ->add('planTitle')
            ->add('planPrice')
            ->add('_action', 'actions',array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            ));
    }

}