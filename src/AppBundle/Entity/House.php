<?php

namespace AppBundle\Entity;

use AppBundle\Mapping\EntityBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * House
 *
 * @ORM\Table(name="house")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HouseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class House extends EntityBase
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
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message="Title cannot be blank")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Agency", inversedBy="houses")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
     */
    private $agency;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area", inversedBy="houses")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id")
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lga", inversedBy="houses")
     * @ORM\JoinColumn(name="lga_id", referencedColumnName="id")
     */
    private $lga;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     * @Assert\NotBlank(message="Price cannot be blank")
     * @Assert\Type("integer", message="Price must be a number")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotBlank(message="Address cannot be blank")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     * @Assert\NotBlank(message="Size cannot be blank")
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="commute", type="string", length=255, nullable=true)
     */
    private $commute;

    /**
     * @var string
     *
     * @ORM\Column(name="essentials", type="string", length=255, nullable=true)
     */
    private $essentials;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="balcony_size", type="string", length=255, nullable=true)
     */
    private $balconySize;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aircon", type="boolean")
     */
    private $aircon;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking", type="boolean")
     */
    private $parking;

    /**
     * @var int
     *
     * @ORM\Column(name="gas", type="boolean")
     */
    private $gas;

    /**
     * @var boolean
     *
     * @ORM\Column(name="water", type="boolean")
     */
    private $water;

    /**
     * @var string
     *
     * @ORM\Column(name="floor", type="string", length=255, nullable=true)
     */
    private $floor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="available", type="boolean")
     */
    private $available;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * @var int
     *
     * @ORM\Column(name="view_count", type="integer")
     */
    private $viewCount;

    /**
     * @var int
     *
     * @ORM\Column(name="like_count", type="integer")
     */
    private $likeCount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="selling", type="boolean")
     */
    private $selling;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Photo", mappedBy="house")
     */
    private $photos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->setViewCount(0);
        $this->setDeleted(false);
        $this->setAvailable(true);
        $this->setStatus(1);
        $this->setLikeCount(0);
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
     * Set status
     *
     * @param integer $status
     *
     * @return House
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return House
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set price
     *
     * @param int $price
     *
     * @return House
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return House
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
     * Set size
     *
     * @param string $size
     *
     * @return House
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set commute
     *
     * @param string $commute
     *
     * @return House
     */
    public function setCommute($commute)
    {
        $this->commute = $commute;

        return $this;
    }

    /**
     * Get commute
     *
     * @return string
     */
    public function getCommute()
    {
        return $this->commute;
    }

    /**
     * Set essentials
     *
     * @param string $essentials
     *
     * @return House
     */
    public function setEssentials($essentials)
    {
        $this->essentials = $essentials;

        return $this;
    }

    /**
     * Get essentials
     *
     * @return string
     */
    public function getEssentials()
    {
        return $this->essentials;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return House
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set balconySize
     *
     * @param string $balconySize
     *
     * @return House
     */
    public function setBalconySize($balconySize)
    {
        $this->balconySize = $balconySize;

        return $this;
    }

    /**
     * Get balconySize
     *
     * @return string
     */
    public function getBalconySize()
    {
        return $this->balconySize;
    }

    /**
     * Set aircon
     *
     * @param integer $aircon
     *
     * @return House
     */
    public function setAircon($aircon)
    {
        $this->aircon = $aircon;

        return $this;
    }

    /**
     * Get aircon
     *
     * @return integer
     */
    public function getAircon()
    {
        return $this->aircon;
    }

    /**
     * Set parking
     *
     * @param integer $parking
     *
     * @return House
     */
    public function setParking($parking)
    {
        $this->parking = $parking;

        return $this;
    }

    /**
     * Get parking
     *
     * @return integer
     */
    public function getParking()
    {
        return $this->parking;
    }

    /**
     * Set gas
     *
     * @param integer $gas
     *
     * @return House
     */
    public function setGas($gas)
    {
        $this->gas = $gas;

        return $this;
    }

    /**
     * Get gas
     *
     * @return integer
     */
    public function getGas()
    {
        return $this->gas;
    }

    /**
     * Set water
     *
     * @param integer $water
     *
     * @return House
     */
    public function setWater($water)
    {
        $this->water = $water;

        return $this;
    }

    /**
     * Get water
     *
     * @return integer
     */
    public function getWater()
    {
        return $this->water;
    }

    /**
     * Set floor
     *
     * @param string $floor
     *
     * @return House
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor
     *
     * @return string
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set available
     *
     * @param integer $available
     *
     * @return House
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return integer
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set viewCount
     *
     * @param integer $viewCount
     *
     * @return House
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * Get viewCount
     *
     * @return integer
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Set likeCount
     *
     * @param integer $likeCount
     *
     * @return House
     */
    public function setLikeCount($likeCount)
    {
        $this->likeCount = $likeCount;

        return $this;
    }

    /**
     * Get likeCount
     *
     * @return integer
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * Set agency
     *
     * @param \AppBundle\Entity\Agency $agency
     *
     * @return House
     */
    public function setAgency(\AppBundle\Entity\Agency $agency = null)
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * Get agency
     *
     * @return \AppBundle\Entity\Agency
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * Add photo
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return House
     */
    public function addPhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \AppBundle\Entity\Photo $photo
     */
    public function removePhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return House
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set forSale
     *
     * @param integer $selling
     *
     * @return House
     */
    public function setSelling($selling)
    {
        $this->selling = $selling;

        return $this;
    }

    /**
     * Get forSale
     *
     * @return integer
     */
    public function getSelling()
    {
        return $this->selling;
    }

    /**
     * Set area
     *
     * @param \AppBundle\Entity\Area $area
     *
     * @return House
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
     * @return House
     */
    public function setLga(\AppBundle\Entity\Lga $lga = null)
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
}
