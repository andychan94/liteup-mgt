<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/30/2018
 * Time: 10:33 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="email_interval")
 */
class EmailInterval
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $lastDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLastDate()
    {
        return $this->lastDate;
    }

    /**
     * @param mixed $lastDate
     */
    public function setLastDate($lastDate)
    {
        $this->lastDate = $lastDate;
    }
}