<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Blog;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use ToolBox\FileBrowserBundle\Form\Type\FileBrowserType;

class BlogAdmin extends AbstractAdmin
{

    public $tbOptions = array(
        'multiple' => false,
        'image_directory' => '/images/blog',
        'thumbWidth' => 600,
        'thumbHeight' => 450,
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
                    "width" => 600,
                    "height" => 450
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
        return $object instanceof Blog
            ? $object->getBlogTitle()
            : 'Blog';
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('isTop')
            ->add('blogTitle')
            ->add('blogShortText')
            ->add('blogText', CKEditorType::class, array(
                'required' => true,
                'label' => 'Blog Text'
            ))
            ->add('blogAuthor')
            ->add('blogMetaKeywords')
            ->add('blogMetaDescription')
            ->add('blogCreatedAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => [
                    'style' => "width:350px"
                ],
            ))
            ->add('blogOgImage', FileBrowserType::class, array(
                'options' => array(
                    'multiple' => false
                )
            ))
            ->add('blogCategory', 'entity', array(
                'class' => 'AppBundle\Entity\BlogCategory',
                'choice_label' => 'blogCategoryTitle',
                'required' => true
            ));

    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('blogShortText')
            ->assertLength(['max' => 255])
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('blogTitle');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('isTop',null,['editable' => true] )
            ->add('blogTitle')
            ->add('blogCreatedAt')
            ->add('blogCategory.blogCategoryTitle')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'blogCreatedAt'
    );
}