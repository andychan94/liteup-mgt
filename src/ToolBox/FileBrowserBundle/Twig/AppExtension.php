<?php

namespace ToolBox\FileBrowserBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class AppExtension extends \Twig_Extension
{

    private $container;
    private $rootDir;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->rootDir = $this->container->get('kernel')->getRootDir() . '/../web';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('file_exists', array($this, 'fileExists')),
            new \Twig_SimpleFilter('json_decode', array($this, 'jsonDecode')),
            new \Twig_SimpleFilter('get_thumb', array($this, 'getThumb')),
        );
    }

    public function fileExists($path)
    {
        return file_exists($this->rootDir.$path);
    }

    public function jsonDecode($json){
        return json_decode($json, true);
    }

    public function getThumb($path, $noImagePath = null){

        $dirName = dirname($path);
        $imageName = basename($path);
        $thumbName = $dirName.'/thumb/'.$imageName;

        if(file_exists($this->rootDir.$thumbName)){
            return $thumbName;
        } else {
            if(file_exists($this->rootDir.$noImagePath)){
                return $noImagePath;
            } else {
                return $path;
            }
        }

    }

}