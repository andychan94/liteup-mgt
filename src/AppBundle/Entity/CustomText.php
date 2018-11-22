<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/17/2018
 * Time: 3:45 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="custom_text")
 */
class CustomText
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $pageName;

    /**
     * @ORM\Column(type="text")
     */
    private $customText;

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
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * @param mixed $pageName
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;
    }

    /**
     * @return mixed
     */
    public function getCustomText()
    {
        return $this->customText;
    }

    /**
     * @param mixed $customText
     */
    public function setCustomText($customText)
    {
        $this->customText = $customText;
    }
}