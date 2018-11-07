<?php

namespace AppBundle\Admin;

use AppBundle\Entity\ContactGroup;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ContactGroupAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['edit', 'show'];

    public function toString($object)
    {
        return $object instanceof ContactGroup
            ? $object->getContactGroupTitle()
            : 'Contact Group'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('contactGroupOrder')
            ->add('contactGroupTitle');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('contactGroupOrder')
            ->add('contactGroupTitle');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('contactGroupOrder')
            ->add('contactGroupTitle')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array()
                )
            ));
    }

    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'contactGroupOrder'
    );

}