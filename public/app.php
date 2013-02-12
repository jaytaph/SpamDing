<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
app_init($app);
app_routes($app);
$app->run();
exit;


function app_init(Silex\Application $app) {
    $app['debug'] = true;

    // Register service controllers
    $app->register(new Silex\Provider\ServiceControllerServiceProvider());

    $app['redis'] = $app->share(function () use($app) {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        return $redis;
    });

    // Register Twig (our template engine)
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path'       => __DIR__.'/../views',
    ));

    // Create controller
    $app['controller.default'] = $app->share(function() use ($app) {
        return new \Spamding\Controller\DefaultController($app);
    });
    $app['controller.mailbox'] = $app->share(function() use ($app) {
        return new \Spamding\Controller\MailboxController($app);
    });


    $app->register(new \Silex\Provider\SessionServiceProvider());
    $app->register(new Silex\Provider\UrlGeneratorServiceProvider());

    $app['session']->start();
}


function app_routes(Silex\Application $app) {
    $app->get('/', 'controller.default:getHomepageAction')->bind("home");
    $app->get('/box/{box}', 'controller.mailbox:getBoxAction')->bind("box");
    $app->get('/box/{box}/{uuid}', 'controller.mailbox:getMessageAction')->bind("message");
}
