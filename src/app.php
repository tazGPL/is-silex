<?php
use Symfony\Component\Debug\ErrorHandler;


$app->register(new Rpodwika\Silex\YamlConfigServiceProvider(__DIR__.'/../app/config/config.yml'));
$app->register(new Rpodwika\Silex\YamlConfigServiceProvider(__DIR__.'/../app/config/members.yml'));
$app['debug'] = $app['config']['app']['debug'];

ErrorHandler::register();
//register an error handler
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    //return your json response here
    $error = array('message' => $e->getMessage(), '1' => $e->getLine(), '2' => $e->getFile());

    return $app['twig']->render('_error.html.twig', []);
});

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../app/views',
));

$app->extend('twig', function($twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Intl());
    return $twig;
});

$app->register(new Silex\Provider\HttpFragmentServiceProvider());

$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'css' => array('version' => 'css3', 'base_path' => '/css'),
        'js' => array('base_path' => '/js'),
        'img' => array('base_path' => '/img'),
    ),
));

// Controller
$app->mount('/', new Is\Controller\MainController());