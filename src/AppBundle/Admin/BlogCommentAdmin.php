<?php

namespace AppBundle\Admin;

use AppBundle\Entity\BlogComment;
use Doctrine\DBAL\Types\BooleanType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ToolBox\FileBrowserBundle\Form\Type\FileBrowserType;

class BlogCommentAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof BlogComment
            ? $object->getBlogCommentName()
            : 'Blog Comment'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('blogCommentName')
            ->add('blog', EntityType::class, array(
                'class' => 'AppBundle:Blog',
                'choice_label' => 'blogTitle'
            ))
            ->add('isApproved')
            ->add('createdAt')
        ;

    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('blogCommentName')
            ->add('blog.blogTitle')
            ->add('isApproved')
            ->add('createdAt')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('blogCommentName')
            ->add('blog.blogTitle')
            ->add('isApproved',null,array(
                'editable'=>true
            ))
            ->add('createdAt')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array(),
                    'delete'=> array()
                )
            ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create');
    }

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt'
    );

}