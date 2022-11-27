<?php

namespace System;

use Closure;

class Application
{
     /**
     *
     * @var array
     */
    private $container = [];

     /**
     *
     * @var \System\Application
     */
    private static $instance;

     /**
     *
     * @param \System\File $file
     */
    private function __construct(File $file)
    {
        $this->share('file', $file);

        $this->registerClasses();

        $this->loadHelpers();
    }

     /**
     *
     * @param \System\File $file
     * @return \System\Application
     */
    public static function getInstance($file = null)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($file);
        }

        return static::$instance;
    }

     /**
     *
     * @return void
     */
    public function run()
    {
        $this->session->start();

        $this->request->prepareUrl();

        $this->file->call('App/index.php');

        list($controller, $method, $arguments) = $this->route->getProperRoute();

        if ($this->route->hasCallsFirst()) {
            $this->route->callFirstCalls();
        }

        $output = (string) $this->load->action($controller, $method, $arguments);

        $this->response->setOutput($output);

        $this->response->send();
    }

     /**
     *
     * @return void
     */
    private function registerClasses()
    {
        spl_autoload_register([$this, 'load']);
    }

     /**
     *
     * @param string $class
     * @return void
     */
    public function load($class)
    {
        if (strpos($class, 'App') === 0) {
            $file = $class . '.php';
        } else {
            // get the class from vendor
            $file = 'vendor/' . $class . '.php';
        }

        if ($this->file->exists($file)) {
            $this->file->call($file);
        }                     
    }

     /**
     *
     * @return void
     */
    private function loadHelpers()
    {
        $this->file->call('vendor/helpers.php');
    }

     /**
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (! $this->isSharing($key)) {
            if ($this->isCoreAlias($key)) {
                $this->share($key, $this->createNewCoreObject($key));
            } else {
                die('<b>' . $key . '</b> not found in application container');
            }
        }

        return $this->container[$key];
    }

     /**
     *
     * @param string $key
     * @return bool
     */
    public function isSharing($key)
    {
        return isset($this->container[$key]);
    }

    /**
    *
    * @param string $key
    * @param mixed $value
    * @return mixed
    */
   public function share($key, $value)
   {
       if ($value instanceof Closure) {
           $value = call_user_func($value, $this);
       }

       $this->container[$key] = $value;
   }

     /**
     *
     * @param string $alias
     * @return bool
     */
    private function isCoreAlias($alias)
    {
        $coreClasses = $this->coreClasses();

        return isset($coreClasses[$alias]);
    }

     /**
     *
     * @param string $alias
     * @return object
     */
    private function createNewCoreObject($alias)
    {
        $coreClasses = $this->coreClasses();

        $object = $coreClasses[$alias];

        return new $object($this);
    }

    /**
    *
    * @return array
    */
   private function coreClasses()
   {
       return [
            'request'       => 'System\\Http\\Request',
            'response'      => 'System\\Http\\Response',
            'session'       => 'System\\Session',
            'route'         => 'System\\Route',
            'cookie'        => 'System\\Cookie',
            'load'          => 'System\\Loader',
            'html'          => 'System\\Html',
            'db'            => 'System\\Database',
            'view'          => 'System\\View\\ViewFactory',
            'url'           => 'System\\Url',
            'validator'     => 'System\\Validation',
            'pagination'    => 'System\\Pagination',
       ];
   }

    /**
    *
    * @param string $key
    * @return mixed
    */
   public function __get($key)
   {
       return $this->get($key);
   }
}