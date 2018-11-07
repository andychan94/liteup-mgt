<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="blog_category")
 */
class BlogCategory
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Blog", mappedBy="blogCategory")
     */
    private $blog;

    /**
     * @ORM\Column(type="integer")
     */
    private $blogCategoryOrder;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"blogCategoryTitle"})
     */
    private $blogCategorySlug;

    /**
     * @ORM\Column(type="string")
     */
    private $blogCategoryTitle;


    /**
     * @ORM\Column(type="string")
     */
    private $blogCategoryMetaKeywords;

    /**
     * @ORM\Column(type="string")
     */
    private $blogCategoryMetaDescription;

    protected $translations;

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
    public function getBlogCategoryOrder()
    {
        return $this->blogCategoryOrder;
    }

    /**
     * @param mixed $blogCategoryOrder
     */
    public function setBlogCategoryOrder($blogCategoryOrder)
    {
        $this->blogCategoryOrder = $blogCategoryOrder;
    }

    /**
     * @return mixed
     */
    public function getBlogCategorySlug()
    {
        return $this->blogCategorySlug;
    }

    /**
     * @param mixed $blogCategorySlug
     */
    public function setBlogCategorySlug($blogCategorySlug)
    {
        $this->blogCategorySlug = $blogCategorySlug;
    }

    /**
     * @return mixed
     */
    public function getBlogCategoryTitle()
    {
        return $this->blogCategoryTitle;
    }

    /**
     * @param mixed $blogCategoryTitle
     */
    public function setBlogCategoryTitle($blogCategoryTitle)
    {
        $this->blogCategoryTitle = $blogCategoryTitle;
    }


    /**
     * @return mixed
     */
    public function getBlogCategoryMetaKeywords()
    {
        return $this->blogCategoryMetaKeywords;
    }

    /**
     * @param mixed $blogCategoryMetaKeywords
     */
    public function setBlogCategoryMetaKeywords($blogCategoryMetaKeywords)
    {
        $this->blogCategoryMetaKeywords = $blogCategoryMetaKeywords;
    }

    /**
     * @return mixed
     */
    public function getBlogCategoryMetaDescription()
    {
        return $this->blogCategoryMetaDescription;
    }

    /**
     * @param mixed $blogCategoryMetaDescription
     */
    public function setBlogCategoryMetaDescription($blogCategoryMetaDescription)
    {
        $this->blogCategoryMetaDescription = $blogCategoryMetaDescription;
    }

}