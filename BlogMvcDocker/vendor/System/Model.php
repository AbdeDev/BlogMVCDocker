<?php

namespace System;

abstract class Model
{
     /**
     *
     * @var \System\Application
     */
    protected $app;

     /**
     *
     * @var string
     */
    protected $table;

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
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }
     /**
     *
     * @return array
     */
    public function all()
    {
        return $this->orderBy('id', 'DESC')->fetchAll($this->table);
    }

     /**
     *
     * @param int $id
     * @return \stdClass | null
     */
    public function get($id)
    {
        return $this->where('id = ?' , $id)->fetch($this->table);
    }

     /**
     *
     * @param mixed $value
     * @param string $key
     * @return bool
     */
    public function exists($value, $key = 'id')
    {
        return (bool) $this->select($key)->where($key .'=?' , $value)->fetch($this->table);
    }

     /**
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        return $this->where('id = ?' , $id)->delete($this->table);
    }

     /**
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }
}