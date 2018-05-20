<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 16/05/2018
 * Time: 03:15
 */

namespace AppBundle\Entity;


use AppBundle\Mapping\EntityBase;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * UserHasHouses
 *
 * @ORM\Table(name="user_house", uniqueConstraints={
 *     @UniqueConstraint(
 *     name="userhouse_idx",
 *     columns={"user_id", "house_id", "action"}
 *     )
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserHasHousesRepository")
 * @UniqueEntity(
 *     fields={"users", "houses", "action"},
 *     errorPath="action",
 *     message="This action is already in use on that house with that user."
 * )
 * @ORM\HasLifecycleCallbacks
 */
class UserHasHouses extends EntityBase
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist","remove"}, fetch="LAZY", inversedBy="hasHouses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=true)
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\House", cascade={"persist"}, fetch="LAZY", inversedBy="hasUsers")
     * @ORM\JoinColumn(name="house_id", referencedColumnName="id", nullable=true)
     */
    protected $houses;


    /**
     * @var string
     * @ORM\Column(name="action", type="string", type="string", nullable=false, length=255)
     */
    protected $action;

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
     * Set action
     *
     * @param string $action
     *
     * @return UserHasHouses
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set houses
     *
     * @param \AppBundle\Entity\House $houses
     *
     * @return UserHasHouses
     */
    public function setHouses(House $houses = null)
    {
        $this->houses = $houses;

        return $this;
    }

    /**
     * Get houses
     *
     * @return \AppBundle\Entity\House
     */
    public function getHouses()
    {
        return $this->houses;
    }

    /**
     * Set users
     *
     * @param \AppBundle\Entity\User $users
     *
     * @return UserHasHouses
     */
    public function setUsers(User $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \AppBundle\Entity\User
     */
    public function getUsers()
    {
        return $this->users;
    }
}
