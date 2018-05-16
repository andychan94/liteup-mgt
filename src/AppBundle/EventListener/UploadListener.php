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

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function onUpload(PostPersistEvent $event)
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