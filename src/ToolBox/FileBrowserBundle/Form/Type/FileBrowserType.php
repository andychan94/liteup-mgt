<?php

namespace ToolBox\FileBrowserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class FileBrowserType extends AbstractType
{

    private $defaultValues = array(
        'options' => array(
            'uplaod_directory' => '',
            'multiple' => true,
            'no_image_file' => '',
            'image_directory' => '/img',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'task_item'
        )
    );

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->defaultValues);
    }

    public function buildView(FormView $view, FormInterface $form, array $options){

        parent::buildView($view, $form, $options);

        $finalOptions = array_merge($this->defaultValues['options'], $options['options']);

        $view->vars = array_merge($view->vars, array(
            'options' => $finalOptions
        ));

    }

    public function setConfig($config){

        $this->defaultValues['options']['no_image_file'] = $config['no_image_file'];
        $this->defaultValues['options']['uplaod_directory'] = $config['default_upload_dir'];

        if(isset($config['image_directory'])){
            $this->defaultValues['options']['uplaod_directory'] = $config['image_directory'];
        }

    }

    public function getParent()
    {
        return HiddenType::class;
    }

}