<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Page;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use ToolBox\FileBrowserBundle\Form\Type\FileBrowserType;

class PageAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['edit', 'show'];


    public function toString($object)
    {
        return $object instanceof Page
            ? $object->getPageTitle()
            : 'Page';
    }

    public $tbOptions = array(
        'multiple' => false,
        'image_directory' => '/images/page',
        'thumbWidth' => 1920,
        'thumbHeight' => 600,
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
                    "width" => 1920,
                    "height" => 600
                )
            )
        )
    );

    public function configure()
    {
        parent::configure();
        $this->setTemplate('edit', 'SonataAdminBundle:CRUD:tb_file_browser_edit.html.twig');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('pageTitle')
            ->add('pageShortText')
            ->add('pageText', CKEditorType::class)
            ->add('pageImage',FileBrowserType::class,array(
                'options' =>array(
                    'multiple' => false
                )
            ));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('pageImage', null, array(
                'template' => 'SonataAdminBundle:CRUD:page_image.html.twig',
                'label' => 'Page Image',
            ))
            ->add('pageTitle')
            ->add('pageShortText')
            ->add('_actions', 'actions', array(
                'actions' => [
                    'edit' => [],
                ]
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
//        $collection
//            ->remove('create')
//            ->remove('delete');
    }
}