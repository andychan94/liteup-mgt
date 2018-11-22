<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Agency;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['edit', 'show'];

    public function toString($object)
    {
        return $object instanceof Agency
            ? $object->getName()
            : "Agency";
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('enabled')
            ->add('blockMessage')
            ->add('username', null)
            ->add('name', null)
            ->add('roles', 'choice', array(
                    'choices' => $this->getConfigurationPool()->getContainer()->getParameter(
                        'security.role_hierarchy.roles'),
                    'choice_label' => function ($choiceValue, $key, $value) {
                            return $choiceValue;
                    },
                    'multiple' => true
                )
            )
            ->add('email')
            ->add('budget',TextType::class)
            ->add('phone')
            ->add('address')
            ->add('services');

    }

    protected function configureListFields(ListMapper $list)
    {

        $list
            ->add('id', null, ['text_align' => 'center', 'row_align' => 'center'])
            ->add('enabled', null, ['label_icon' => 'fa fa-check', 'text_align' => 'center', 'editable' => true])
            ->add('logo', null, array(
                'label' => 'Image',
                'template' => 'SonataAdminBundle:CRUD:list_image.html.twig',
                'header_style' => 'width: 80px',
                'label_icon' => 'fa fa-image',
            ))
            ->add('username', null, ['label_icon' => 'fa fa-user-secret', 'text_align' => 'center',])
            ->add('name', null, ['label_icon' => 'fa fa-user-secret', 'text_align' => 'center',])
            ->add('email', null, ['label_icon' => 'fa fa-envelope', 'text_align' => 'center',])
            ->add('phone', null, ['label_icon' => 'fa fa-phone', 'text_align' => 'center',])
            ->add('createdAt', null, ['label_icon' => 'fa fa-clock-o'])
            ->add('_actions', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
        ;
    }

    protected function configureShowFields(ShowMapper $show)
    {


        $show
            ->with('Information', ['class' => 'col-md-8'])
            ->add('enabled')
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('about')
            ->add('services')
            ->add('roles')
            ->add('budget')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('lastLogin')
            ->end()
            ->with('image', ['class' => 'col-md-4    '])
            ->add('logo', null, array('template' => 'SonataAdminBundle:CRUD:list_image.html.twig'))
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('enabled', null, ['show_filter' => true])
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('roles', null, ['show_filter' => true], 'choice', array(
                    'choices' => $this->getConfigurationPool()->getContainer()->getParameter(
                        'security.role_hierarchy.roles'),
                    'choice_label' => function ($choiceValue, $key, $value) {
                        if ($choiceValue != 'ROLE_USER')
                            return $choiceValue;
                    },
                )
            );
    }

    protected $datagridValues = [
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'createdAt'  // name of the ordered field
        // (default = the model's id field, if any)

    ];

}
