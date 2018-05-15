<?php

namespace AppBundle\Entity;

use AppBundle\Mapping\EntityBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Area
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends EntityBase
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
     * @ORM\Column(name="device_id", type="string", length=255, unique=true)
     */
    private $deviceId;

    /**
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lga")
     * @ORM\JoinColumn(name="lga_id", referencedColumnName="id", nullable=false, onDelete="SET NULL")
     */
    private $lga;

    /**
     * @ORM\Column(name="address", type="string", length=255, unique=true)
     */
    private $address;

    /**
     * @ORM\Column(name="phone", type="string", length=255, unique=true)
     */
    private $phone;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\House")
     */
    protected $views;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->views = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set deviceId
     *
     * @param string $deviceId
     *
     * @return User
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * Get deviceId
     *
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set area
     *
     * @param \AppBundle\Entity\Area $area
     *
     * @return User
     */
    public function setArea(\AppBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \AppBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set lga
     *
     * @param \AppBundle\Entity\Lga $lga
     *
     * @return User
     */
    public function setLga(\AppBundle\Entity\Lga $lga)
    {
        $this->lga = $lga;

        return $this;
    }

    /**
     * Get lga
     *
     * @return \AppBundle\Entity\Lga
     */
    public function getLga()
    {
        return $this->lga;
    }

    /**
     * Add view
     *
     * @param \AppBundle\Entity\House $view
     *
     * @return User
     */
    public function addView(\AppBundle\Entity\House $view)
    {
        $this->views[] = $view;

        return $this;
    }

    /**
     * Remove view
     *
     * @param \AppBundle\Entity\House $view
     */
    public function removeView(\AppBundle\Entity\House $view)
    {
        $this->views->removeElement($view);
    }

    /**
     * Get views
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getViews()
    {
        return $this->views;
    }
}
