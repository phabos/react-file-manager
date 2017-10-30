<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entities\File;

class FileController
{
    protected $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }

    public function listAction(Request $request)
    {
        $nbElement = 10;
        $skip = ( isset( $_GET['skip'] ) && $_GET['skip'] > 1 ? ($_GET['skip']-1) : 0 ) * $nbElement;
        $images = File::skip($skip)->take($nbElement)->orderBy('id', 'DESC')->get();
        $totalPage = ($nbElement > 0 ? ceil(File::count() / $nbElement) : 0 );
        $files = [];
        if( !empty( $images ) ) {
            foreach ($images as $image) {
                $json['path'] = $image->path;
                $json['title'] = '/files/' . $image->title;
                $json['uploaded'] = $image->uploaded;
                $files[] = $json;
            }
        }
        return $this->app->json([
            'total' => $totalPage,
            'files' => $files
        ], 200);
    }

    public function uploadAction(Request $request)
    {
        if( !empty( $request->files ) ) {
            foreach ($request->files->all() as $file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $this->app['file.service']->uploadFile($file);
            }
        }

        if( !empty( $request->request->get('croppedImg') ) ) {
            $img = explode(',', $request->request->get('croppedImg'));
            if( isset( $img[1] ) ) {
                try {
                    $file = new Base64EncodedFile($img[1]);
                    $this->app['file.service']->uploadFile($file);
                } catch (FileException $e) {
                    return $this->app->json([
                        'message' => 'An error occured ' . $e->getMessage()
                    ], 500);
                }
            }
        }

        return $this->app->json([
            'message' => 'Everything worked fine'
        ], 200);
    }
}
