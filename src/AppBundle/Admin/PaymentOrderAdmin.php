<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/15/2018
 * Time: 4:17 PM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PaymentOrderAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('user.username')
            ->add('payId')
            ->add('amount')
            ->add('ipAddress')
            ->add('authorizationCode')
            ->add('bin')
            ->add('last4')
            ->add('expMonth')
            ->add('expYear')
            ->add('chanel')
            ->add('cardType')
            ->add('bank')
            ->add('countryCode')
            ->add('brand')
            ->add('customerId')
            ->add('customerEmail')
            ->add('customerCode')
            ->add('paidAt')
            ->add('createdAt')
            ->add('transactionDate');

    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create');
    }

    protected $datagridValues = [
        '_sort_by' => 'DESC',
        '_sort_order' => 'paidAt',
    ];
}