<?php

namespace AppBundle\Entity;

use AppBundle\Mapping\EntityBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\JoinColumn(name="lga_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $lgaIdUser;

    /**
     * @ORM\Column(name="address", type="string", length=255, unique=true)
     */
    private $address;

    /**
     * @ORM\Column(name="phone", type="string", length=255, unique=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserHasHouses", mappedBy="users", cascade={"persist","remove"})
     */
    protected $hasHouses;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\HouseInspection", mappedBy="user")
     */
    protected $houseInspection;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\HouseInspection", mappedBy="user")
     */
    protected $callRequest;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserHouse", mappedBy="user", )
     */
    protected $userHouse;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hasHouses = new ArrayCollection();
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
    public function setArea(Area $area = null)
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
     * @param \AppBundle\Entity\Lga $lgaIdUser
     *
     * @return User
     */
    public function setLgaIdUser(Lga $lgaIdUser)
    {
        $this->lgaIdUser = $lgaIdUser;

        return $this;
    }

    /**
     * Get lga
     *
     * @return \AppBundle\Entity\Lga
     */
    public function getLgaIdUser()
    {
        return $this->lgaIdUser;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add hasHouse
     *
     * @param \AppBundle\Entity\UserHasHouses $hasHouse
     *
     * @return User
     */
    public function addHasHouse(UserHasHouses $hasHouse)
    {
        $this->hasHouses[] = $hasHouse;

        return $this;
    }

    /**
     * Remove hasHouse
     *
     * @param \AppBundle\Entity\UserHasHouses $hasHouse
     */
    public function removeHasHouse(UserHasHouses $hasHouse)
    {
        $this->hasHouses->removeElement($hasHouse);
    }

    /**
     * Get hasHouses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHasHouses()
    {
        return $this->hasHouses;
    }
}
