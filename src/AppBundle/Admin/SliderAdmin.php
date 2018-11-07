<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/8/2018
 * Time: 1:17 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\Slider;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use ToolBox\FileBrowserBundle\Form\Type\FileBrowserType;

class SliderAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof Slider
            ? $object->getSliderText1()
            : 'Slider';
    }

    public $tbOptions = array(
        'multiple' => false,
        'image_directory' => '/images/slider',
        'thumbWidth' => 1920,
        'thumbHeight' => 1080,
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
                    "height" => 1080
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
            ->add('sliderText1')
            ->add('sliderText2')
            ->add('sliderImage', FileBrowserType::class, array(
                'options' => array(
                    'multiple' => true
                )
            ));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('sliderText1')
            ->add('sliderText2')
            ->add('_action', 'actions', array(
                'actions' => [
                    'edit' => array(),
                    'delete' => array(),
                ]
            ));
    }

}