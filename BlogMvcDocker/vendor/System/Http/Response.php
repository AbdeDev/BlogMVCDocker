<?php

namespace System\Http;

use System\Application;

class Response
{
     /**
     *
     * @var \System\Application
     */
    private $app;

     /**
     *
     * @var array
     */
    private $headers = [];

     /**
     *
     * @var string
     */
    private $content = '';

     /**
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

     /**
     *
     * @param string $content
     * @return void
     */
    public function setOutput($content)
    {
        $this->content = $content;
    }

     /**
     *
     * @param string $header
     * @param mixed value
     * @return void
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

     /**
     *
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();

        $this->sendOutput();
    }

     /**
     *
     * @return void
     */
    private function sendHeaders()
    {    
        foreach ($this->headers as $header => $value) {
            header($header . ':' . $value);
        }
    }

     /**
     *
     * @return void
     */
    private function sendOutput()
    {
        echo $this->content;
    }
}