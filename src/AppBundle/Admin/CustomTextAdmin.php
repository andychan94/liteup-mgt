<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/17/2018
 * Time: 3:49 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\CustomText;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CustomTextAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof CustomText
            ? $object->getPageName()
            :'Text';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('pageName')
            ->add('customText',CKEditorType::class);
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('pageName')
            ->add('_action', 'actions',array(
                'actions' => array(
                    'edit' => array(),
                )
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('delete');
    }

}