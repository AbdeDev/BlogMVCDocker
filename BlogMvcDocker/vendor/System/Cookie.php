<?php

namespace System;

class Cookie
{
     /**
     *
     * @var \System\Application
     */
    private $app;

     /**
     *
     * @var string
     */
    private $path = '/';

     /**
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->path = dirname($this->app->request->server('SCRIPT_NAME')) ?: '/';
    }

     /**
     *
     * @param string $key
     * @param mixed $value
     * @param int $hours
     * @return void
     */
    public function set($key, $value, $hours = 1800)
    {
        $expireTime = $hours == -1 ? -1 : time() + $hours * 3600;

        setcookie($key, $value, $expireTime, $this->path, '', false, true);
    }

     /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key , $default = null)
    {
        return array_get($_COOKIE, $key, $default);
    }

     /**
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $_COOKIE);
    }

     /**
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        $this->set($key, null, -1);

        unset($_COOKIE[$key]);
    }

     /**
     *
     * @return array
     */
    public function all()
    {
        return $_COOKIE;
    }

     /**
     *
     * @return void
     */
    public function destroy()
    {
        foreach (array_keys($this->all()) AS $key) {
            $this->remove($key);
        }

        unset($_COOKIE);
    }
}