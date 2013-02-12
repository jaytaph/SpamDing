<?php

namespace Spamding\Model;

class Mailbox {
    protected $app;
    protected $name;
    protected $messages;

    public function __construct($app, $box) {
        $this->app = $app;
        $this->messages = array();

        $this->setName($box);
        $this->_load($box);
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function addMessage(\Spamding\Model\Message $message) {
        $this->messages[] = $message;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function _load() {
        $redis = $this->app['redis'];
        $keys = $redis->keys("mailbox-".$this->getName()."-*");
        foreach ($keys as $key) {
            $email = unserialize($redis->get($key));

            $message = new \Spamding\Model\Message($email['id'], $email['headers'], $email['body']);
            $this->messages[] = $message;
        }
    }

    public function storeMessage(\Spamding\Model\Message $message) {
        $redis = $this->app['redis'];

        $tmp = array("id" => $message->getId(),
                     "headers" => $message->getRawHeaders(),
                     "body" => $message->getBody());
        $redis->set("mailbox-".$this->getName()."-".$message->getId(), serialize($tmp));
    }

}
