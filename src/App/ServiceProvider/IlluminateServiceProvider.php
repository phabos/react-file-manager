<?php

namespace App\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class IlluminateServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $app)
    {}

    public function boot(Application $app)
    {
        new Illuminate($app['db.setup']);
    }
}
