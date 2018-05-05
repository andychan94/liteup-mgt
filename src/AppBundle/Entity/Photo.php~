<?php

namespace AppBundle\Entity;

use AppBundle\Mapping\EntityBase;
use Doctrine\ORM\Mapping as ORM;

/**
 * House
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Photo extends EntityBase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="House", inversedBy="photos")
     * @ORM\JoinColumn(name="house_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $house;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, unique=true)
     */
    private $path;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Photo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set house
     *
     * @param \AppBundle\Entity\House $house
     *
     * @return Photo
     */
    public function setHouse(\AppBundle\Entity\House $house = null)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get house
     *
     * @return \AppBundle\Entity\House
     */
    public function getHouse()
    {
        return $this->house;
    }
}
