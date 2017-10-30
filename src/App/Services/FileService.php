<?php

namespace App\Services;

use App\Entities\File;

class FileService
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function uploadFile($file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
        $this->app['upload.dir'],
            $fileName
        );
        $file = new File();
        $file->path = $this->app['upload.dir'] . $fileName;
        $file->title = $fileName;
        $file->uploaded = new \DateTime();
        $file->save();
    }
}

