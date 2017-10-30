<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entities\Post;

class PostController
{
    protected $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }

    public function homeAction()
    {
        return $this->app['twig']->render('post-create.twig', []);
    }
}
