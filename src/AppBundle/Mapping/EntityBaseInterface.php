<?php

namespace AppBundle\Mapping;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * EntityBase Interface
 *
 * @author Lelle - Daniele Rostellato <lelle.daniele@gmail.com>
 */
interface EntityBaseInterface
{
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps();

    /**
     * Get createdAt
     *
     * @return null|DateTime
     */
    public function getCreatedAt();

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt);

    /**
     * Get updatedAt
     *
     * @param DateTime $createdAt
     * @return self
     */
    public function getUpdatedAt();

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt);
}