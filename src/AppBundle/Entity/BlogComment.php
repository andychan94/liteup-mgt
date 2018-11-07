<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_comment")
 * @ORM\HasLifecycleCallbacks()
 */
class BlogComment
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Blog", inversedBy="blogComment")
     */

    private $blog;


    /**
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\Agency", inversedBy="blogComment")
     */
    private $user;

    /**
     *
     * @ORM\Column(type="string")
     */
    private $blogCommentName;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $isApproved = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param mixed $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
    }

    /**
     * @return mixed
     */
    public function getBlogCommentName()
    {
        return $this->blogCommentName;
    }

    /**
     * @param mixed $blogCommentName
     */
    public function setBlogCommentName($blogCommentName)
    {
        $this->blogCommentName = $blogCommentName;
    }


    /**
     * @return mixed
     */
    public function getisApproved()
    {
        return $this->isApproved;
    }

    /**
     * @param mixed $isApproved
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


}