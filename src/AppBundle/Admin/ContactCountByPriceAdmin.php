<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/23/2018
 * Time: 4:27 PM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ContactCountByPriceAdmin extends AbstractAdmin
{


        protected function configureFormFields(FormMapper $form)
        {
            $form
                ->add('minSalePrice')
                ->add('maxSalePrice')
                ->add('minRentPrice')
                ->add('maxRentPrice')
                ->add('minShortPrice')
                ->add('maxShortPrice');
        }

        protected function configureListFields(ListMapper $list)
        {
            $list
                ->add('minSalePrice')
                ->add('maxSalePrice')
                ->add('minRentPrice')
                ->add('maxRentPrice')
                ->add('minShortPrice')
                ->add('maxShortPrice')
                ->add('_action','actions',array(
                    'actions' => array(
                        'edit' => array()
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