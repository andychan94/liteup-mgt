<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/12/2018
 * Time: 3:32 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\HelpSupport;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use ToolBox\FileBrowserBundle\Form\Type\FileBrowserType;

class HelpSupportAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof HelpSupport
            ? $object->getTitle()
            : 'Help';

    }

    public $tbOptions = array(
        'multiple' => false,
        'image_directory' => '/images/help-support',
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

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('helpSupportOrder')
            ->add('title')
            ->add('document', FileBrowserType::class, array(
                'options' => array(
                    'multiple' => false,
                )
            ));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('helpSupportOrder', null,['editable' => true])
            ->add('title')
            ->add('document', null,['template' => 'SonataAdminBundle:CRUD:help_support_pdf.html.twig'])
            ->add('_actions','actions',array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }
}