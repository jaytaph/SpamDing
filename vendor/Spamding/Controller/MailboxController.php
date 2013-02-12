<?php
namespace Spamding\Controller;

use Symfony\Component\HttpFoundation\Request;

class MailboxController {

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getBoxAction(Request $request, $box)
    {
        $mailbox = new \Spamding\Model\Mailbox($this->app, $box);

        return $this->app['twig']->render('mailbox/box.html.twig', array("mailbox" => $mailbox));
    }

}