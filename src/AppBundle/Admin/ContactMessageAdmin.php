<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/27/2018
 * Time: 11:51 AM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\ContactMessage;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class ContactMessageAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['edit', 'show'];

    public function toString($object)
    {
        return $object instanceof ContactMessage
            ? $object->getName()
            : "Message";
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('name')
            ->add('email')
            ->add('createdAt')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'delete' => [],
                ]
            ]);
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('name')
            ->add('email')
            ->add('message')
            ->add('createdAt');
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    protected $datagridValues = [
        '_sort_order' => 'DESC',

        '_sort_by' => 'createdAt',
    ];

}