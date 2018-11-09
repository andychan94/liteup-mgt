<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/8/2018
 * Time: 3:07 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\ImageSecurity;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ImageSecurityAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof ImageSecurity
            ? $object->getConfidence()
            : 'Security name'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->add('validate')
            ->add('confidence');

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('validate',null,['editable' => true,['label' => 'na']])
            ->add('confidence')
            ->add('_action','actions',array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('validate')
            ->add('confidence')

        ;
    }

}