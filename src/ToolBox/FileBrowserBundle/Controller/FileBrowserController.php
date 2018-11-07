<?php

namespace ToolBox\FileBrowserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ToolBox\FileBrowserBundle\Service\FileTreeBrowser;
use Symfony\Component\DependencyInjection\Container;

class FileBrowserController extends Controller
{

    private $fileTreeBrowserService;
    protected $container;
    private $rootDir;

    public function __construct(FileTreeBrowser $fileTreeBrowser, Container $container)
    {

        $this->container = $container;
        $this->fileTreeBrowserService = $fileTreeBrowser;
        $this->rootDir = $this->container->get('kernel')->getRootDir() . '/../web';

    }



    public function getFileTreeAction(Request $request)
    {

        $fileTree = $this->fileTreeBrowserService->getFileTree($request->get('dir'));

        return new Response($fileTree);

    }

    public function getDirectoryContentAction(Request $request)
    {

        $dirContent = $this->fileTreeBrowserService->getDirContent($request->get('dir'));

        return new Response($dirContent);

    }

    public function uploadFileAction(Request $request){

        $file = $request->files->get('img');
        $path = $request->get('dir');

        $uploadStatus = $this->fileTreeBrowserService->uploadFile($file, $path);

        $jsonResponse = array();

        if($uploadStatus['success'] == 'true'){
            $jsonResponse = array(
                'success' => 'true',
                'message' => $uploadStatus['message']
            );
        } else {
            $jsonResponse = array(
                'success' => 'false',
                'message' => $uploadStatus['message']
            );
        }

        return new JsonResponse($jsonResponse);

    }

    public function cropImageAction(Request $request){

        $jsonResponse = array();

        $path = $request->get('file_path');

        if(isset($path)){

            $iniW = $request->get('ini_width');
            $iniH = $request->get('ini_height');
            $resW = $request->get('res_width');
            $resH = $request->get('res_height');
            $cropW = $request->get('crop_width');
            $cropH = $request->get('crop_height');
            $cropX = $request->get('scaleX');
            $cropY = $request->get('scaleY');

            $file_upload = $this->fileTreeBrowserService->resizeImage($this->rootDir.$path, $this->rootDir.$path, $cropW, $cropH, $iniW, $iniH, $cropX, $cropY);

            if($file_upload){

                $jsonResponse = array(
                    'success' => 'true',
                    'message' => 'File is successfuly cropped!'
                );

            } else {

                $jsonResponse = array(
                    'success' => 'false',
                    'message' => 'File is not cropped, please try again!'
                );

            }

        } else {

            $jsonResponse = array(
                'success' => 'false',
                'message' => 'Please specify file path!'
            );

        }

        return new JsonResponse($jsonResponse);

    }

    public function createDirAction(Request $request){

        $jsonResponse = array();
        $path = $request->get('dir');

        if($path){

            $newDir = $this->fileTreeBrowserService->createDir($path);

            if($newDir['success']){
                $jsonResponse = array(
                    'success' => 'true',
                    'message' => $newDir['message']
                );
            } else {
                $jsonResponse = array(
                    'success' => 'false',
                    'message' => $newDir['message']
                );
            }

        } else {

            $jsonResponse = array(
                'success' => 'false',
                'message' => 'Directory is not created!'
            );

        }

        return new JsonResponse($jsonResponse);

    }

    public function deleteFileAction(Request $request){

        $jsonResponse = array();
        $file = $request->get('file');

        $fileDelete = $this->fileTreeBrowserService->deleteFile($file);

        if($fileDelete['success']){

            $jsonResponse = array(
                'success' => 'true',
                'message' => $fileDelete['message']
            );

        } else {

            $jsonResponse = array(
                'success' => 'false',
                'message' => $fileDelete['message']
            );

        }

        return new JsonResponse($jsonResponse);

    }

    public function deleteTreeAction(Request $request){

        $jsonResponse = array();
        $dir = $request->get('dir');

        if(isset($dir) && !empty($dir)){

            if(file_exists($this->rootDir.$dir)){

                $deleteTree = $this->fileTreeBrowserService->deleteTree($this->rootDir.$dir);

                if($deleteTree){

                    $jsonResponse = array(
                        'success' => 'true',
                        'message' => 'Selected directory is successfully deleted.'
                    );

                } else {

                    $jsonResponse = array(
                        'success' => 'false',
                        'message' => 'Selected directory is not deleted please try again!'
                    );

                }

            } else {

                $jsonResponse = array(
                    'success' => 'false',
                    'message' => 'File or directory doesn\'t exist'
                );

            }

        } else {

            $jsonResponse = array(
                'success' => 'false',
                'message' => 'Directory is not specified!'
            );

        }

        return new JsonResponse($jsonResponse);

    }

}