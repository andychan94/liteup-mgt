<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/22/2018
 * Time: 12:15 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\Email;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmailAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof Email
        ? $object->getEmailSubject()
        :"Email";
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('emailStatus')
            ->add('emailName')
            ->add('emailSubject')
            ->add('emailText', null,array(
                'attr' => array(
                    'rows' => '8',
                )
            ))
            ->add('InSystemAlert',TextType::class,array('required' => false))
//            ->add('emailText', CKEditorType::class)
            ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id')
            ->add('emailStatus')
            ->add('emailName')
            ->add('emailSubject')
            ->add('_action','actions',array(
                'actions' => array(
                    'edit' => array()
                )
            ))
            ;
    }

}