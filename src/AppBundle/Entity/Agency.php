<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * House
 *
 * @ORM\Table(name="agency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgencyRepository")
 * @UniqueEntity(
 *     fields={"phone", "address", "email"}
 * )
 * @UniqueEntity(fields="phone")
 * @UniqueEntity(fields="address")
 * @UniqueEntity(fields="email")
 * @Vich\Uploadable
 */
class Agency extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter the last name of the agency.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The last name is too short.",
     *     maxMessage="The last name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Please enter the your phone number.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The number is too short.",
     *     maxMessage="The number is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $phone;

    /**
     * @var int
     *
     * @ORM\Column(name="budget", type="bigint", nullable=false, options={"default" : 0})
     */
    protected $budget;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The address is too short.",
     *     maxMessage="The address is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    protected $logo;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "The maxmimum allowed file size is 2MB.",
     *     mimeTypesMessage = "Only image files are allowed."
     * )
     * @Vich\UploadableField(mapping="agency_logo", fileNameProperty="logo", size="logoSize")
     *
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    protected $logoSize;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    protected $about;

    /**
     * @var string
     *
     * @ORM\Column(name="services", type="text", nullable=true)
     */
    protected $services;

    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="House", mappedBy="agency")
     */
    protected $houses;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\VerifyRequest", mappedBy="agency", fetch="EXTRA_LAZY")
     */
    protected $verifyRequest;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PaymentOrder", mappedBy="user")
     *
     */
    protected $paymentOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $deactivate = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deactivateAt;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BlogComment", mappedBy="user")
     */
    protected $blogComment;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\UserPlan", mappedBy="user")
     */
    protected $userPlan;

    /**
     * @ORM\Column(type="string")
     */
    protected $userType;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserHouseRequest", mappedBy="agency")
     */
    protected $house;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $firstLogin = null;


    /**
     * @ORM\Column(type="integer")
     */
    protected $totalBudget;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $totalBudgetLimitAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $noCoin = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $blockMessage = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $userStatus = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_ADMIN');
        $this->houses = new ArrayCollection();
        $this->setBudget(4000);
        $this->setTotalBudget(4000);
        $this->setLogo("");
        $this->setLogoSize(0);
        $this->setAbout("");
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    public function setEmail($email)
    {
        $this->setUsername($email);
        return parent::setEmail($email); // TODO: Change the autogenerated stub
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Agency
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
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Agency
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
     * Set budget
     *
     * @param integer $budget
     *
     * @return Agency
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Agency
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Agency
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
     * @return Agency
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
     * Add house
     *
     * @param \AppBundle\Entity\House $house
     *
     * @return Agency
     */
    public function addHouse(House $house)
    {
        $this->houses[] = $house;

        return $this;
    }

    /**
     * Remove house
     *
     * @param \AppBundle\Entity\House $house
     */
    public function removeHouse(House $house)
    {
        $this->houses->removeElement($house);
    }

    /**
     * Get houses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHouses()
    {
        return $this->houses;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Agency
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set about
     *
     * @param string $about
     *
     * @return Agency
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile($image = null)
    {
        $this->imageFile = $image;

        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set logoSize
     *
     * @param integer $logoSize
     *
     * @return Agency
     */
    public function setLogoSize($logoSize)
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    /**
     * Get logoSize
     *
     * @return integer
     */
    public function getLogoSize()
    {
        return $this->logoSize;
    }

    /**
     * Set services
     *
     * @param string $services
     *
     * @return Agency
     */
    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Get services
     *
     * @return string
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return mixed
     */
    public function getVerifyRequest()
    {
        return $this->verifyRequest;
    }

    /**
     * @param mixed $verifyRequest
     */
    public function setVerifyRequest($verifyRequest)
    {
        $this->verifyRequest = $verifyRequest;
    }

    /**
     * @return mixed
     */

    public function getDeactivate()
    {
        return $this->deactivate;
    }

    /**
     * @param mixed $deactivate
     */
    public function setDeactivate($deactivate)
    {
        $this->deactivate = $deactivate;
    }

    /**
     * @return mixed
     */
    public function getDeactivateAt()
    {
        return $this->deactivateAt;
    }

    /**
     * @param mixed $deactivateAt
     */
    public function setDeactivateAt($deactivateAt)
    {
        $this->deactivateAt = $deactivateAt;
    }

    /**
     * @return mixed
     */
    public function getBlogComment()
    {
        return $this->blogComment;
    }

    /**
     * @param mixed $blogComment
     */
    public function setBlogComment($blogComment)
    {
        $this->blogComment = $blogComment;
    }

    /**
     * @return mixed
     */
    public function getUserPlan()
    {
        return $this->userPlan;
    }

    /**
     * @param mixed $userPlan
     */
    public function setUserPlan($userPlan)
    {
        $this->userPlan = $userPlan;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param mixed $house
     */
    public function setHouse($house)
    {
        $this->house = $house;
    }

    /**
     * @return mixed
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    /**
     * @param mixed $firstLogin
     */
    public function setFirstLogin($firstLogin)
    {
        $this->firstLogin = $firstLogin;
    }

    /**
     * @return mixed
     */
    public function getTotalBudget()
    {
        return $this->totalBudget;
    }

    /**
     * @param mixed $totalBudget
     */
    public function setTotalBudget($totalBudget)
    {
        $this->totalBudget = $totalBudget;
    }

    /**
     * @return mixed
     */
    public function getTotalBudgetLimitAt()
    {
        return $this->totalBudgetLimitAt;
    }

    /**
     * @param mixed $totalBudgetLimitAt
     */
    public function setTotalBudgetLimitAt($totalBudgetLimitAt)
    {
        $this->totalBudgetLimitAt = $totalBudgetLimitAt;
    }

    /**
     * @return mixed
     */
    public function getNoCoin()
    {
        return $this->noCoin;
    }

    /**
     * @param mixed $noCoin
     */
    public function setNoCoin($noCoin)
    {
        $this->noCoin = $noCoin;
    }

    /**
     * @return mixed
     */
    public function getBlockMessage()
    {
        return $this->blockMessage;
    }

    /**
     * @param mixed $blockMessage
     */
    public function setBlockMessage($blockMessage)
    {
        $this->blockMessage = $blockMessage;
    }

    /**
     * @return mixed
     */
    public function getUserStatus()
    {
        return $this->userStatus;
    }

    /**
     * @param mixed $userStatus
     */
    public function setUserStatus($userStatus)
    {
        $this->userStatus = $userStatus;
    }



}
