<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 13/05/2018
 * Time: 23:49
 */

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

class FeatureType extends AbstractType
{
//    /**
//     * @return  string
//     */
//    public function getName()
//    {
//        return 'feature';
//    }

    /**
     * @return  string
     */
    public function getParent()
    {
        return EntityType::class;
    }

}