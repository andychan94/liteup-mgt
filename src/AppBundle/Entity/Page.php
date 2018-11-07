<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class Page {

    const SERVER_PATH_TO_IMAGE_FOLDER = "%kernel.root_dir%/../images/page";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $pageTitle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $pageShortText;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pageText;

    /**
     * @ORM\Column(type="string")
     */
    private $pageImage;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return mixed
     */
    public function getPageShortText()
    {
        return $this->pageShortText;
    }

    /**
     * @param mixed $pageShortText
     */
    public function setPageShortText($pageShortText)
    {
        $this->pageShortText = $pageShortText;
    }

    /**
     * @return mixed
     */
    public function getPageText()
    {
        return $this->pageText;
    }

    /**
     * @param mixed $pageText
     */
    public function setPageText($pageText)
    {
        $this->pageText = $pageText;
    }

    /**
     * @return mixed
     */
    public function getPageImage()
    {
        return $this->pageImage;
    }

    /**
     * @param mixed $pageImage
     */
    public function setPageImage($pageImage)
    {
        $this->pageImage = $pageImage;
    }



}