<?php

namespace AppBundle\Entity;

use AppBundle\Mapping\EntityBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Area
 *
 * @ORM\Table(name="lga")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LgaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Lga extends EntityBase
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
     * @ORM\ManyToOne(targetEntity="State", inversedBy="lgas")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="Area", mappedBy="lga")
     */
    protected $areas;

    /**
     * @ORM\OneToMany(targetEntity="House", mappedBy="lga")
     */
    protected $houses;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->areas = new ArrayCollection();
        $this->houses = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Lga
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
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     *
     * @return Lga
     */
    public function setState(State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add area
     *
     * @param \AppBundle\Entity\Area $area
     *
     * @return Lga
     */
    public function addArea(Area $area)
    {
        $this->areas[] = $area;

        return $this;
    }

    /**
     * Remove area
     *
     * @param \AppBundle\Entity\Area $area
     */
    public function removeArea(Area $area)
    {
        $this->areas->removeElement($area);
    }

    /**
     * Get areas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * Add house
     *
     * @param \AppBundle\Entity\House $house
     *
     * @return Lga
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
     * Set position
     *
     * @param integer $position
     *
     * @return Lga
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
