<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 08/05/2018
 * Time: 10:56
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\House;
use AppBundle\Entity\Photo;
use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;
    private $container;

    public function __construct(ObjectManager $om,ContainerInterface $container)
    {
        $this->om = $om;
        $this->container = $container;
    }

    public function onUpload(PostPersistEvent $event)
    {

        $request = $event->getRequest();
        $houseId = $request->get('house');
        $house = $this->om->getRepository(House::class)->find($houseId);
        $path = $event->getFile();
        $file = basename($path);
        $file_path = $this->container->get('kernel')->getRootDir().'/../web/images/uploads/' .$file;
        $logo = $this->container->get('kernel')->getRootDir().'/../web/images/bg-image/liteup-logo.png';
        $api_credentials = array(
            'key' => 'acc_e90c6b9538f91c8',
            'secret' => 'cb573abb2e9bcdc9f4a1e65bc071a7dd'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.imagga.com/v2/tags");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERPWD, $api_credentials['key'].':'.$api_credentials['secret']);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        $fields = [
            'image' => new \CurlFile($file_path, 'image/jpeg', 'image.jpg')
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $response = curl_exec($ch);
        curl_close($ch);

        $json_response = json_decode($response,true);

        $statusText =$json_response['status']['text'];
        $statusType =$json_response['status']['type'];
        $stamp = imagecreatefrompng($logo);
        $im = imagecreatefromjpeg($file_path);

        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
        header('Content-type: image/png');
        imagepng($im,$file_path);
        $imageSecurity = $this->om->getRepository('AppBundle:ImageSecurity')->findBy(['validate' => 1]);
        foreach ($json_response['result']['tags'] as $item) {
            foreach ($imageSecurity as $secureConfidence){
                if ($secureConfidence->getConfidence() != $item['tag']['en']){
                    $object = new Photo();
                    $object->setPath($file);
                    $object->setHouse($house);
                    $this->om->persist($object);
                    $this->om->flush();
                    $response = $event->getResponse();
                    $response['success'] = true;
                    return $response;
                }else{
                    unlink($file_path);
                    return false;
                }
            }
        }

//        $object = new Photo();
//        $object->setPath($file);
//        $object->setHouse($house);
//        $this->om->persist($object);
//        $this->om->flush();
//        $response = $event->getResponse();
//        $response['success'] = $file;
//
//        return $response;
    }

    public function onDelete(PostPersistEvent $event)
    {
        $request = $event->getRequest();
        $houseId = $request->get('house');
        $house = $this->om->getRepository(House::class)->find($houseId);
        $path = $event->getFile();
        $file = basename($path);
        $object = new Photo();
        $object->setPath($file);
        $object->setHouse($house);

        $this->om->persist($object);
        $this->om->flush();
        $response = $event->getResponse();
        $response['success'] = true;

        return $response;
    }

}