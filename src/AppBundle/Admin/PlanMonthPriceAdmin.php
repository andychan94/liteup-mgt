<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/6/2018
 * Time: 1:41 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\PlanMonthPrice;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PlanMonthPriceAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof PlanMonthPrice
            ? $object->getPrice()
            : 'Plan';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('price')
            ->add('monthCount');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('price')
            ->add('monthCount')
            ->add('_action','actions',array(
                'actions' =>array(
                    'edit' => array(),
                    'delete' => array()
                )
            ))
        ;
    }

}