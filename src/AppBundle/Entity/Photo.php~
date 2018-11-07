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
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * Photo constructor.
     */
    public function __construct()
    {
        $this->setPosition(0);
    }

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
    public function setHouse(House $house = null)
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

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Photo
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
}
