<?php

namespace System\View;

use System\Application;

class ViewFactory
{
     /**
     *
     * @var \System\Application
     */
    private $app;

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
    * @param string $viewPath
    * @param array $data
    * @return \System\View\ViewInterface
    */
    public function render($viewPath, array $data = [])
    {
        return new View($this->app->file, $viewPath, $data);
    }
}