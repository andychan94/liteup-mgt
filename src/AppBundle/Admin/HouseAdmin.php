<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/10/2018
 * Time: 5:51 PM
 */

namespace AppBundle\Admin;


use AppBundle\Entity\House;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class HouseAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof House
            ? $object->getTitle()
            : 'Blog Post'; // shown in the breadcrumb on the create view
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('showOnTop', null, ['editable' => true])
            ->add('isDeleted')
            ->add('deactivate')
            ->add('isShort')
            ->add('isRent')
            ->add('isBuy')
            ->add('title')
            ->add('createdAt')
            ->add('_actions', 'actions', array(
                'actions' => array(
                    'show' => array(),
                )
            ));
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->with('Info', ['class' => 'col-md-5'])
            ->add('showOnTop')
            ->add('status')
            ->add('title')
            ->add('description')
            ->add('agency.name')
            ->add('area.name')
            ->add('lgaId.name')
            ->add('lgaId.state.name')
            ->add('address')
            ->add('isShort')
            ->add('priceShort')
            ->add('isRent')
            ->add('priceRent')
            ->add('isBuy')
            ->add('priceBuy')
            ->add('kind')
            ->add('bedrooms')
            ->add('bathrooms')
            ->add('toilets')
            ->add('isResidential')
            ->add('isAvailable')
            ->add('features.name')
            ->add('isDeleted')
            ->add('deactivate')
            ->add('createdAt')
            ->add('updatedAt')
            ->end()
            ->with('Photos', ['class' => 'col-md-7', ])
            ->add('photos', null, ['template' => 'SonataAdminBundle:CRUD:house_image.html.twig',])
            ->end();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('isDeleted')
            ->add('deactivate')
            ->add('bedrooms')
            ->add('bathrooms')
            ->add('toilets')
            ->add('showOnTop')
        ;

    }

    protected $datagridValues = [

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'createdAt',
    ];
}