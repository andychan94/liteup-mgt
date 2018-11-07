<?php

namespace AppBundle\Admin;

use AppBundle\Entity\BlogCategory;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use ToolBox\FileBrowserBundle\Form\Type\FileBrowserType;

class BlogCategoryAdmin extends AbstractAdmin
{

    public $tbOptions = array(
        'multiple' => false,
        'image_directory' => '/images/blog',
        'thumbWidth' => 800,
        'thumbHeight' => 800,
        'cropOptions' => array(
            0 => array(
                'og' => array(
                    "title" => "Open Graph (facebook)",
                    "type" => "pixel",
                    "width" => 1200,
                    "height" => 630
                ),
                'thumb' => array(
                    "title" => "Thumbnail",
                    "type" => "pixel",
                    "width" => 800,
                    "height" => 800
                )
            )
        )
    );

    public function configure()
    {
        parent::configure();
        $this->setTemplate('edit', 'SonataAdminBundle:CRUD:tb_file_browser_edit.html.twig');
    }

    public function toString($object)
    {
        return $object instanceof BlogCategory
            ? $object->getBlogCategoryTitle()
            : 'Blog Category'; // shown in the breadcrumb on the create view
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('blogCategoryOrder')
            ->add('blogCategoryTitle')
            ->add('blogCategoryMetaKeywords')
            ->add('blogCategoryMetaDescription')

        ;

    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('blogCategoryTitle')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('blogCategoryOrder',null,['editable' => true])
            ->add('blogCategoryTitle')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            ))
        ;
    }
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }

    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'blogCategoryOrder'
    );

}