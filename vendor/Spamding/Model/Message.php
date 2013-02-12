<?php

namespace Spamding\Model;

class Message {
    protected $id;
    protected $body;
    protected $raw_headers;
    protected $headers;

    protected function _uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public function __construct($id, $headers, $body) {
        if ($id == 0) $id = $this->_uuid();
        $this->id = $id;
        $this->body = $body;
        $this->raw_headers = $headers;

        $headers = preg_replace('/\r\n\s+/m', '', $headers);
        $headers = trim($headers) . "\r\n";
        preg_match_all('/([^: ]+): (.+?(?:[\r|\n|\r\n]\s(?:.+?))*)?[\r|\n|\r\n]/m', $headers, $matches);

        $this->headers = array();
        foreach ($matches[1] as $key =>$value) {
            $this->headers[strtolower($value)]=$matches[2][$key];
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getFrom()
    {
        return $this->headers['from'];
    }

    public function getRawHeaders()
    {
        return $this->raw_headers;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getSubject()
    {
        return $this->headers['subject'];
    }

    public function getTo()
    {
        return $this->headers['to'];
    }

    public function getHeaderField($field)
    {
        return $this->headers[$field];
    }



}
