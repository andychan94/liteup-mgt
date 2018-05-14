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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Photo", mappedBy="house")
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Feature")
     */
    protected $features;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDeleted(false);
        $this->setAvailable(true);
        $this->setStatus(1);
        $this->setIsResidential(1);
        $this->photos = new ArrayCollection();
        $this->features = new ArrayCollection();
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
     * Add feature
     *
     * @param \AppBundle\Entity\Feature $feature
     *
     * @return House
     */
    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;

        return $this;
    }

    /**
     * Remove feature
     *
     * @param \AppBundle\Entity\Feature $feature
     */
    public function removeFeature(Feature $feature)
    {
        $this->features->removeElement($feature);
    }

    /**
     * Get features
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }
}
