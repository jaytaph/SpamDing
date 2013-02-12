<?php
namespace Spamding\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController {

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getHomepageAction()
    {
        return $this->app['twig']->render('index.html.twig');
    }

}