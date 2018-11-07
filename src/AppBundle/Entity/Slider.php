<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/8/2018
 * Time: 1:13 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="slider")
 */
class Slider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $sliderText1;
    /**
     * @ORM\Column(type="string")
     */
    private $sliderText2;

    /**
     * @ORM\Column(type="json_array")
     */
    private $sliderImage;

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
    public function getSliderText1()
    {
        return $this->sliderText1;
    }

    /**
     * @param mixed $sliderText1
     */
    public function setSliderText1($sliderText1)
    {
        $this->sliderText1 = $sliderText1;
    }

    /**
     * @return mixed
     */
    public function getSliderText2()
    {
        return $this->sliderText2;
    }

    /**
     * @param mixed $sliderText2
     */
    public function setSliderText2($sliderText2)
    {
        $this->sliderText2 = $sliderText2;
    }

    /**
     * @return mixed
     */
    public function getSliderImage()
    {
        return $this->sliderImage;
    }

    /**
     * @param mixed $sliderImage
     */
    public function setSliderImage($sliderImage)
    {
        $this->sliderImage = $sliderImage;
    }

}