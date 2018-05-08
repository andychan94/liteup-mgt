<?php

namespace AppBundle\Entity;

use AppBundle\Enum\HouseBathroomsEnum;
use AppBundle\Enum\HouseBedroomsEnum;
use AppBundle\Enum\HouseKindEnum;
use AppBundle\Enum\HouseToiletsEnum;
use AppBundle\Enum\HouseTypeEnum;
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
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $agency;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area", inversedBy="houses")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lga", inversedBy="houses")
     * @ORM\JoinColumn(name="lga_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $lga;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Assert\NotBlank(message="Address cannot be blank")
     */
    private $address;

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
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="kind", type="string", length=255, nullable=false)
     */
    private $kind;

    /**
     * @var string
     *
     * @ORM\Column(name="bedrooms", type="integer", length=255, nullable=false)
     */
    private $bedrooms;

    /**
     * @var string
     *
     * @ORM\Column(name="bathrooms", type="integer", length=255, nullable=false)
     */
    private $bathrooms;

    /**
     * @var string
     *
     * @ORM\Column(name="toilets", type="integer", length=255, nullable=false)
     */
    private $toilets;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65500, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_residential", type="boolean")
     */
    private $isResidential;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aircon", type="boolean")
     */
    private $aircon;

    /**
     * @var boolean
     *
     * @ORM\Column(name="balcony", type="boolean")
     */
    private $balcony;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fence", type="boolean")
     */
    private $fence;

    /**
     * @var boolean
     *
     * @ORM\Column(name="garage", type="boolean")
     */
    private $garage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="garden", type="boolean")
     */
    private $garden;

    /**
     * @var boolean
     *
     * @ORM\Column(name="swpool", type="boolean")
     */
    private $swpool;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking", type="boolean")
     */
    private $parking;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fountain", type="boolean")
     */
    private $fountain;

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
        $this->setIsResidential(1);
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
     * Set type
     *
     * @param string $type
     *
     * @return House
     */
    public function setType($type)
    {
        if (!in_array($type, HouseTypeEnum::getAvailableTypes())) {
            throw new \InvalidArgumentException("Invalid type");
        }
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
     * @param Agency $agency
     *
     * @return House
     */
    public function setAgency(Agency $agency = null)
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
    public function addPhoto(Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \AppBundle\Entity\Photo $photo
     */
    public function removePhoto(Photo $photo)
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
     * @param \AppBundle\Entity\Lga $lga
     *
     * @return House
     */
    public function setLga(Lga $lga = null)
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
     * Set isResidential
     *
     * @param boolean $isResidential
     *
     * @return House
     */
    public function setIsResidential($isResidential)
    {
        $this->isResidential = $isResidential;

        return $this;
    }

    /**
     * Get isResidential
     *
     * @return boolean
     */
    public function getIsResidential()
    {
        return $this->isResidential;
    }

    /**
     * Set kind
     *
     * @param string $kind
     *
     * @return House
     */
    public function setKind($kind)
    {
        if (!in_array($kind, HouseKindEnum::getAvailableTypes())) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get kind
     *
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set bedrooms
     *
     * @param integer $bedrooms
     *
     * @return House
     */
    public function setBedrooms($bedrooms)
    {
        if (!in_array($bedrooms, HouseBedroomsEnum::getAvailableTypes())) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->bedrooms = $bedrooms;

        return $this;
    }

    /**
     * Get bedrooms
     *
     * @return integer
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * Set bathrooms
     *
     * @param integer $bathrooms
     *
     * @return House
     */
    public function setBathrooms($bathrooms)
    {
        if (!in_array($bathrooms, HouseBathroomsEnum::getAvailableTypes())) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->bathrooms = $bathrooms;

        return $this;
    }

    /**
     * Get bathrooms
     *
     * @return integer
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * Set toilets
     *
     * @param integer $toilets
     *
     * @return House
     */
    public function setToilets($toilets)
    {
        if (!in_array($toilets, HouseToiletsEnum::getAvailableTypes())) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->toilets = $toilets;

        return $this;
    }

    /**
     * Get toilets
     *
     * @return integer
     */
    public function getToilets()
    {
        return $this->toilets;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return House
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set balcony
     *
     * @param boolean $balcony
     *
     * @return House
     */
    public function setBalcony($balcony)
    {
        $this->balcony = $balcony;

        return $this;
    }

    /**
     * Get balcony
     *
     * @return boolean
     */
    public function getBalcony()
    {
        return $this->balcony;
    }

    /**
     * Set fence
     *
     * @param boolean $fence
     *
     * @return House
     */
    public function setFence($fence)
    {
        $this->fence = $fence;

        return $this;
    }

    /**
     * Get fence
     *
     * @return boolean
     */
    public function getFence()
    {
        return $this->fence;
    }

    /**
     * Set garage
     *
     * @param boolean $garage
     *
     * @return House
     */
    public function setGarage($garage)
    {
        $this->garage = $garage;

        return $this;
    }

    /**
     * Get garage
     *
     * @return boolean
     */
    public function getGarage()
    {
        return $this->garage;
    }

    /**
     * Set garden
     *
     * @param boolean $garden
     *
     * @return House
     */
    public function setGarden($garden)
    {
        $this->garden = $garden;

        return $this;
    }

    /**
     * Get garden
     *
     * @return boolean
     */
    public function getGarden()
    {
        return $this->garden;
    }

    /**
     * Set swpool
     *
     * @param boolean $swpool
     *
     * @return House
     */
    public function setSwpool($swpool)
    {
        $this->swpool = $swpool;

        return $this;
    }

    /**
     * Get swpool
     *
     * @return boolean
     */
    public function getSwpool()
    {
        return $this->swpool;
    }

    /**
     * Set fountain
     *
     * @param boolean $fountain
     *
     * @return House
     */
    public function setFountain($fountain)
    {
        $this->fountain = $fountain;

        return $this;
    }

    /**
     * Get fountain
     *
     * @return boolean
     */
    public function getFountain()
    {
        return $this->fountain;
    }
}
