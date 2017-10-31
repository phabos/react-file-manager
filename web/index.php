<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new App\ServiceProvider\IlluminateServiceProvider(), [
  'db.setup' => [
      'driver'    => 'mysql',
      'host'      => 'mysql',
      'database'  => 'silex',
      'username'  => 'root',
      'password'  => 'root',
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => ''
  ]
]);

$app['upload.dir'] = realpath(dirname(__DIR__)).'/web/files/';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => realpath(dirname(__DIR__)).'/tpl'
));

$app['posts.controller'] = function() use ($app) {
    return new App\Controllers\PostController($app);
};

$app['comments.controller'] = function() use ($app) {
    return new App\Controllers\CommentsController($app);
};

$app['file.service'] = function() use ($app) {
    return new App\Services\FileService($app);
};

$app['files.controller'] = function() use ($app) {
    return new App\Controllers\FileController($app);
};

$app->get('/', 'posts.controller:homeAction')->bind('home');
$app->get('/file/list', 'files.controller:listAction')->bind('file_list');
$app->post('/file/upload', 'files.controller:uploadAction')->bind('file_upload');
$app->get('/comment/add', 'comments.controller:addAction')->bind('comment_add');
$app->get('/comment/list', 'comments.controller:listAction')->bind('comment_list');

$app->run();
