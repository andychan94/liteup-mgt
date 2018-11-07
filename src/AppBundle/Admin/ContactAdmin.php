<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Contact;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContactAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['edit', 'show'];

    public function toString($object)
    {
        return $object instanceof Contact
            ? $object->getContactInfo()
            : 'Contact'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('contactOrder')
            ->add('contactInfo')
            ->add('contactGroup', EntityType::class, array(
                'class' => 'AppBundle:ContactGroup',
                'choice_label' => 'contactGroupTitle'
            ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('contactOrder',null,['editable' => true])
            ->add('contactInfo')
            ->add('contactGroup.contactGroupTitle')
//            ->add('isPrimary',null,['editable' => true])
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            ))
        ;
    }

    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'contactOrder'
    );

}