<?php

namespace System;

abstract class Controller
{
     /**
     *
     * @var \System\Application
     */
    protected $app;

    /**
    *
    * @var array
    */
    protected $errors = [];

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
    * @param mixed $data
    * @return string
    */
    public function json($data)
    {
        return json_encode($data);
    }

     /**
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }
}