<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/8/2018
 * Time: 3:03 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image_security")
 */
class ImageSecurity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validate = false;

    /**
     * @ORM\Column(type="string")
     */
    private $confidence;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * @param mixed $validate
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    /**
     * @return mixed
     */
    public function getConfidence()
    {
        return $this->confidence;
    }

    /**
     * @param mixed $confidence
     */
    public function setConfidence($confidence)
    {
        $this->confidence = $confidence;
    }
}