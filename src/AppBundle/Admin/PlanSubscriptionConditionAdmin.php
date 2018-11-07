<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/5/2018
 * Time: 1:05 PM
 */

namespace AppBundle\Admin;

use AppBundle\Entity\PlanSubscriptionCondition;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PlanSubscriptionConditionAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof PlanSubscriptionCondition
            ? $object->getConditionTitle()
            : 'Plan';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('conditionOrder')
            ->add('conditionTitle');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('conditionOrder', null, ['editable' => true])
            ->add('conditionTitle')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            ));
    }

}