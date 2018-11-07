<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/1/2018
 * Time: 1:52 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\VerificationCondition;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class VerificationConditionAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof VerificationCondition
            ? $object->getVerificationConditionText()
            : 'Verification Condition';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('isTop',null,['attr' => ['checked' => 'checked']])
            ->add('verificationConditionOrder')
            ->add('verificationConditionText');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('isTop',null,['editable' => true])
            ->add('verificationConditionOrder',null,['editable' => true])
            ->add('verificationConditionText')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }
}