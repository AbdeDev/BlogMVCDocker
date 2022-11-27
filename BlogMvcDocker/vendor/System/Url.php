<?php

namespace System;

class Url
{
     /**
     *
     * @var \System\Application
     */
    protected $app;

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
     * @param string $path
     * @return string
     */
    public function link($path)
    {
        return $this->app->request->baseUrl() . trim($path, '/');
    }

     /**
     *
     * @param string $path
     * @return void
     */
    public function redirectTo($path)
    {
        header('location:' . $this->link($path));

        exit;
    }  
}