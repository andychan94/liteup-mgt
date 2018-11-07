<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 26/04/2018
 * Time: 17:43
 */

namespace AppBundle\Mapping;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EntityBase
 *
 * PHP version 7.1
 *
 * LICENSE: MIT
 *
 * @package    AppBundle\Mapping
 * @author     Lelle - Daniele Rostellato <lelle.daniele@gmail.com>
 * @license    MIT
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 * @ORM\HasLifecycleCallbacks
 */
class EntityBase implements EntityBaseInterface
{
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
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $dateTimeNow = new DateTime('now');
        $this->setUpdatedAt($dateTimeNow);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}