<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/17/2018
 * Time: 3:30 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\PlanBuyCoin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PlanBuyCoinAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof PlanBuyCoin
            ? $object->getPlanTitle()
            : 'Plan';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('planTitle')
            ->add('planDescription', null, array(
                'attr' => [
                    'rows' => '5'
                ]
            ))
            ->add('planPrice', CollectionType::class, array(
                'mapped' => true,
                'allow_add' => true,
                'entry_type' => TextType::class,
                'allow_delete' => true,
                'label' => 'Coins Amount',
                'label_attr' => array('class' => 'Coins Amount'),
            ));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('planTitle')
            ->add('planDescription')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array()
                )
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
//            ->remove('delete')
//            ->remove('create')
        ;
    }
}