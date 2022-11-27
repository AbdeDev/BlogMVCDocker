<?php

namespace System;

class Route
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
    private $routes = [];

     /**
     *
     * @var array
     */
    private $current = [];

     /**
     *
     * @var string
     */
    private $notFound;

     /**
     *
     * @var array
     */
    private $calls = [];

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
    * @return array
    */
    public function routes()
    {
        return $this->routes;
    }

     /**
     *
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @return void
     */
    public function add($url, $action, $requestMethod = 'GET')
    {
        $route = [
            'url'       => $url,
            'pattern'   => $this->generatePattern($url),
            'action'    => $this->getAction($action),
            'method'    => strtoupper($requestMethod),
        ];

        $this->routes[] = $route;
    }

     /**
     *
     * @param string $url
     * @return void
     */
    public function notFound($url)
    {
        $this->notFound = $url;
    }

     /**
     *
     * @var callable $callable
     * @return $this
     */
    public function callFirst(callable $callable)
    {
        $this->calls['first'][] = $callable;

        return $this;
    }

     /**
     *
     * @return bool
     */
    public function hasCallsFirst()
    {
        return ! empty($this->calls['first']);
    }

     /**
     *
     * @return bool
     */
    public function callFirstCalls()
    {
        foreach ($this->calls['first'] AS $callback) {
            call_user_func($callback, $this->app);
        }
    }

    /**
    *
    * @return array
    */
   public function getProperRoute()
   {
       foreach ($this->routes as $route) {
           if ($this->isMatching($route['pattern']) AND $this->isMatchingRequestMethod($route['method'])) {
               $arguments = $this->getArgumentsFrom($route['pattern']);

               // controller@method
               list($controller, $method) = explode('@', $route['action']);

               $this->current = $route;

               return [$controller, $method, $arguments];
           }
       }

       return $this->app->url->redirectTo($this->notFound);
   }

    /**
    *
    * @return string
    */
   public function getCurrentRouteUrl()
   {
       return $this->current['url'];
   }

    /**
    *
    * @param string $pattern
    * @return bool
    */
   private function isMatching($pattern)
   {
       return preg_match($pattern, $this->app->request->url());
   }

   /**
   *
   * @param string $routeMethod
   * @return bool
   */
   private function isMatchingRequestMethod($routeMethod)
   {
       return $routeMethod == $this->app->request->method();
   }

    /**
    *
    * @param string $pattern
    * @return array
    */
   private function getArgumentsFrom($pattern)
   {
       preg_match($pattern, $this->app->request->url(), $matches);

       array_shift($matches);

       return $matches;
   }

     /**
     *
     * @param string $url
     * @return string
     */
    private function generatePattern($url)
    {
        $pattern = '#^';


        $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'] , $url);

        $pattern .= '$#';

        return $pattern;
    }

     /**
     *
     * @param string $action
     * @return string
     */
    private function getAction($action)
    {
        $action = str_replace('/' , '\\', $action);

        return strpos($action, '@') !== false ? $action : $action . '@index';
    }
}