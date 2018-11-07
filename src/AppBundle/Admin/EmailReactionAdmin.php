<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/23/2018
 * Time: 12:57 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\EmailReaction;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmailReactionAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof EmailReaction
            ? $object->getEmailSubject()
            : "Email";
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('emailStatus')
            ->add('emailName')
            ->add('emailSubject')
            ->add('emailText', null, array(
                'attr' => array(
                    'rows' => '8'
                )
            ))
            ->add('InSystemAlert', TextType::class, array(
                'required' => false,
            ));

    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id')
            ->add('emailStatus')
            ->add('emailName')
            ->add('emailSubject')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array()
                )
            ));
    }

}