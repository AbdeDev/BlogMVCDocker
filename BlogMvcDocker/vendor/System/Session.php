<?php

namespace System;

class Session
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
     * @return void
     */
    public function start()
    {
        ini_set('session.use_only_cookies', 1);

        if (! session_id()) {
            session_start();
        }
    }

     /**
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

     /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key , $default = null)
    {
        return array_get($_SESSION, $key, $default);
    }

     /**
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

     /**
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

     /**
     *
     * @param string $key
     * @return mixed
     */
    public function pull($key)
    {
        $value = $this->get($key);

        $this->remove($key);

        return $value;
    }

     /**
     *
     * @return array
     */
    public function all()
    {
        return $_SESSION;
    }

     /**
     *
     * @return void
     */
    public function destroy()
    {
        session_destroy();

        unset($_SESSION);
    }
}