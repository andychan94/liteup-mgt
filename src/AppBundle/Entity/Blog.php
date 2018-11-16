<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog")
 * @ORM\HasLifecycleCallbacks()
 */
class Blog
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BlogCategory", inversedBy="blog")
     */
    private $blogCategory;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BlogComment", mappedBy="blog")
     */
    private $blogComment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTop = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdminDashboard = false;

    /**
     * @ORM\Column(type="string",unique=true)
     * @Gedmo\Slug(fields={"blogTitle"})
     */
    private $blogSlug;

    /**
     * @ORM\Column(type="string")
     */
    private $blogTitle;

    /**
     * @ORM\Column(type="string")
     */
    private $blogAuthor;

    /**
     * @ORM\Column(type="string")
     */
    private $blogShortText;


    /**
     * @ORM\Column(type="text")
     */
    private $blogText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $blogCreatedAt;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $blogOgImage;

    /**
     * @ORM\Column(type="string")
     */
    private $blogMetaKeywords;

    /**
     * @ORM\Column(type="string")
     */
    private $blogMetaDescription;


    public function __construct()
    {
        $this->blogCreatedAt = new \DateTime();
    }

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
    public function getBlogCategory()
    {
        return $this->blogCategory;
    }

    /**
     * @param mixed $blogCategory
     */
    public function setBlogCategory($blogCategory)
    {
        $this->blogCategory = $blogCategory;
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
    public function getisTop()
    {
        return $this->isTop;
    }

    /**
     * @param mixed $isTop
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;
    }

    /**
     * @return mixed
     */
    public function getBlogSlug()
    {
        return $this->blogSlug;
    }

    /**
     * @param mixed $blogSlug
     */
    public function setBlogSlug($blogSlug)
    {
        $this->blogSlug = $blogSlug;
    }

    /**
     * @return mixed
     */
    public function getBlogTitle()
    {
        return $this->blogTitle;
    }

    /**
     * @param mixed $blogTitle
     */
    public function setBlogTitle($blogTitle)
    {
        $this->blogTitle = $blogTitle;
    }

    /**
     * @return mixed
     */
    public function getBlogAuthor()
    {
        return $this->blogAuthor;
    }

    /**
     * @param mixed $blogAuthor
     */
    public function setBlogAuthor($blogAuthor)
    {
        $this->blogAuthor = $blogAuthor;
    }

    /**
     * @return mixed
     */
    public function getBlogShortText()
    {
        return $this->blogShortText;
    }

    /**
     * @param mixed $blogShortText
     */
    public function setBlogShortText($blogShortText)
    {
        $this->blogShortText = $blogShortText;
    }

    /**
     * @return mixed
     */
    public function getBlogText()
    {
        return $this->blogText;
    }

    /**
     * @param mixed $blogText
     */
    public function setBlogText($blogText)
    {
        $this->blogText = $blogText;
    }

    /**
     * @return mixed
     */
    public function getBlogCreatedAt()
    {
        return $this->blogCreatedAt;
    }

    /**
     * @param mixed $blogCreatedAt
     */
    public function setBlogCreatedAt($blogCreatedAt)
    {
        $this->blogCreatedAt = $blogCreatedAt;
    }

    /**
     * @return mixed
     */
    public function getBlogOgImage()
    {
        return $this->blogOgImage;
    }

    /**
     * @param mixed $blogOgImage
     */
    public function setBlogOgImage($blogOgImage)
    {
        $this->blogOgImage = $blogOgImage;
    }

    /**
     * @return mixed
     */
    public function getBlogMetaKeywords()
    {
        return $this->blogMetaKeywords;
    }

    /**
     * @param mixed $blogMetaKeywords
     */
    public function setBlogMetaKeywords($blogMetaKeywords)
    {
        $this->blogMetaKeywords = $blogMetaKeywords;
    }

    /**
     * @return mixed
     */
    public function getBlogMetaDescription()
    {
        return $this->blogMetaDescription;
    }

    /**
     * @param mixed $blogMetaDescription
     */
    public function setBlogMetaDescription($blogMetaDescription)
    {
        $this->blogMetaDescription = $blogMetaDescription;
    }

    /**
     * @return mixed
     */
    public function getisAdminDashboard()
    {
        return $this->isAdminDashboard;
    }

    /**
     * @param mixed $isAdminDashboard
     */
    public function setIsAdminDashboard($isAdminDashboard)
    {
        $this->isAdminDashboard = $isAdminDashboard;
    }


}