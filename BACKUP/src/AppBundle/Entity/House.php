<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * House
 *
 * @ORM\Table(name="house")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HouseRepository")
 */
class House
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
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Agency", inversedBy="houses")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
     */
    private $agency;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=255)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="commute", type="string", length=255)
     */
    private $commute;

    /**
     * @var string
     *
     * @ORM\Column(name="essentials", type="string", length=255)
     */
    private $essentials;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="balcony_size", type="string", length=255)
     */
    private $balconySize;

    /**
     * @var int
     *
     * @ORM\Column(name="aircon", type="boolean")
     */
    private $aircon;

    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="water", type="boolean")
     */
    private $water;

    /**
     * @var string
     *
     * @ORM\Column(name="floor", type="string", length=255)
     */
    private $floor;

    /**
     * @var int
     *
     * @ORM\Column(name="available", type="boolean")
     */
    private $available;

    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="for_sale", type="integer")
     */
    private $forSale;

    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Photo", mappedBy="house")
     */
    private $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->setViewCount(0);
        $this->setDeleted(0);
        $this->setAvailable(1);
        $this->setLikeCount(0);
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
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
     * @param string $price
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
     * @return string
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return House
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return House
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * @param integer $forSale
     *
     * @return House
     */
    public function setForSale($forSale)
    {
        $this->forSale = $forSale;

        return $this;
    }

    /**
     * Get forSale
     *
     * @return integer
     */
    public function getForSale()
    {
        return $this->forSale;
    }
}
