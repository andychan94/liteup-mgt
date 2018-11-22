<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/17/2018
 * Time: 11:29 AM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\VerificationType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use ToolBox\FileBrowserBundle\Form\Type\FileBrowserType;

class VerificationTypeAdmin extends AbstractAdmin
{
    public $tbOptions = array(
        'multiple' => false,
        'image_directory' => '/images/x',
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
        return $object instanceof VerificationType
            ? $object->getVerificationTypeText()
            : 'Verification Type';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('verificationTypeText')
            ->add('verificationTypeDocument', FileBrowserType::class, array(
                'options' => array(
                    'multiple' => false
                )
            ));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('verificationTypeText')
            ->add('_action','actions',array(
                'actions' => array(
                    'edit' => array(),
                )
            ))

        ;
    }


    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete');
    }
}