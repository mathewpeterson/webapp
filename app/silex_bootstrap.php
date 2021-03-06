<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$env = getenv('SYMFONY_ENV') ?: 'dev';
$app['debug'] = in_array($env, ['dev', 'development', 'testing', 'test']);

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new \OpenRepeater\Legacy\Provider\LegacyProvider());

/**
 * @see http://gonzalo123.com/2013/03/04/scaling-silex-applications-part-ii-using-routecollection/
 */
$app['routes'] = $app->extend('routes', function (RouteCollection $routes, Silex\Application $app) {
    $loader  = new YamlFileLoader(new FileLocator(__DIR__ . '/../src/OpenRepeater/Legacy/Resources/config'));

    $collection = $loader->load('routing.yml');
    $routes->addCollection($collection);

    return $routes;
});

return $app;