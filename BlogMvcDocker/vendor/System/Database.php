<?php

namespace System;

use PDO;
use PDOException;

class Database
{
     /**
     *
     * @var \System\Application
     */
    private $app;

     /**
     *
     * @var \PDO
     */
    private static $connection;

     /**
     *
     * @var string
     */
    private $table;

     /**
     *
     * @var array
     */
    private $data = [];

     /**
     *
     * @var array
     */
    private $bindings = [];

     /**
     *
     * @var int
     */
    private $lastId;

     /**
     *
     * @var array
     */
    private $wheres = [];

     /**
     *
     * @var array
     */
    private $havings = [];

     /**
     *
     * @var array
     */
    private $groupBy = [];

     /**
     *
     * @var array
     */
    private $selects = [];

     /**
     *
     * @var int
     */
    private $limit;

     /**
     *
     * @var int
     */
    private $offset;

     /**
     *
     * @var int
     */
    private $rows = 0;

     /**
     *
     * @var array
     */
    private $joins = [];

     /**
     *
     * @array
     */
    private $orerBy = [];

     /**
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (! $this->isConnected()) {
            $this->connect();
        }
    }

     /**
     *
     * @return bool
     */
     private function isConnected()
     {
         return static::$connection instanceof PDO;
     }

     /**
     *
     * @return void
     */
     private function connect()
     {
         $connectionData = $this->app->file->call('config.php');

         extract($connectionData);

         try {
             static::$connection = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass);

             static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

             static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            static::$connection->exec('SET NAMES utf8');
         } catch (PDOException $e) {
             die($e->getMessage());
         }
     }

     /**
     *
     * @return \PDO
     */
     public function connection()
     {
         return static::$connection;
     }

     /**
     *
     * @return $this
     */
     public function select(...$selects)
     {
         $selects = func_get_args();

         $this->selects = array_merge($this->selects, $selects);

         return $this;
     }

     /**
     *
     * @param string $join
     * @return $this
     */
     public function join($join)
     {
         $this->joins[] = $join;

         return $this;
     }

     /**
     *
     * @param int $limit
     * @param int $offset
     * @return $this
     */
     public function limit($limit, $offset = 0)
     {
         $this->limit = $limit;

         $this->offset = $offset;

         return $this;
     }

     /**
     *
     * @param string $column
     * @param string $sort
     * @return $this
     */
     public function orderBy($orderBy, $sort = 'ASC')
     {
         $this->orerBy = [$orderBy, $sort];

         return $this;
     }

      /**
      *
      * @param string $table
      * @return \stdClass | null
      */
     public function fetch($table = null)
     {
         if ($table) {
             $this->table($table);
         }

         $sql = $this->fetchStatement();

         $result = $this->query($sql, $this->bindings)->fetch();

         $this->reset();

         return $result;
     }

      /**
      *
      * @param string $table
      * @return array
      */
     public function fetchAll($table = null)
     {
         if ($table) {
             $this->table($table);
         }

         $sql = $this->fetchStatement();

         $query = $this->query($sql, $this->bindings);

         $results = $query->fetchAll();

         $this->rows = $query->rowCount();

         $this->reset();

         return $results;
     }

      /**
      *
      * @return int
      */
     public function rows()
     {
         return $this->rows;
     }

      /**
      *
      * @return string
      */
     private function fetchStatement()
     {
         $sql = 'SELECT ';

         if ($this->selects) {
             $sql .= implode(',' , $this->selects);
         } else {
             $sql .= '*';
         }

         $sql .= ' FROM ' . $this->table . ' ';

         if ($this->joins) {
             $sql .= implode(' ' , $this->joins);
         }

         if ($this->wheres) {
             $sql .= ' WHERE ' . implode(' ', $this->wheres) . ' ';
         }

         if ($this->havings) {
             $sql .= ' HAVING ' . implode(' ', $this->havings) . ' ';
         }

         if ($this->orerBy) {
             $sql .= ' ORDER BY ' . implode(' ' , $this->orerBy);
         }

         if ($this->limit) {
             $sql .= ' LIMIT ' . $this->limit;
         }

         if ($this->offset) {
             $sql .= ' OFFSET ' . $this->offset;
         }
                                               
         if ($this->groupBy) {
             $sql .= ' GROUP BY ' . implode(' ' , $this->groupBy);
         }


         return $sql;
     }

      /**
      *
      * @param string $table
      * @return $this
      */
     public function table($table)
     {
         $this->table = $table;

         return $this;
     }

      /**
      *
      * @param string $table
      * @return $this
      */
     public function from($table)
     {
         return $this->table($table);
     }
     /**
     *
     * @param string $table
     * @return $this
     */
     public function delete($table = null)
     {
         if ($table) {
             $this->table($table);
         }

         $sql = 'DELETE FROM ' . $this->table . ' ';

         if ($this->wheres) {
             $sql .= ' WHERE ' . implode(' ' , $this->wheres);
         }

         $this->query($sql, $this->bindings);

         $this->reset();

         return $this;
     }

      /**
      *
      * @param mixed $key
      * @param mixed $value
      * @return $this
      */
     public function data($key, $value = null)
     {
         if (is_array($key)) {
             $this->data = array_merge($this->data, $key);

             $this->addToBindings($key);
         } else {
             $this->data[$key] = $value;

             $this->addToBindings($value);
         }

         return $this;
     }

     /**
     *
     * @param string $table
     * @return $this
     */
     public function insert($table = null)
     {
         if ($table) {
             $this->table($table);
         }

         $sql = 'INSERT INTO ' . $this->table . ' SET ';

         $sql .= $this->setFields();

         $this->query($sql, $this->bindings);

         $this->lastId = $this->connection()->lastInsertId();

         $this->reset();

         return $this;
     }

     /**
     *
     * @param string $table
     * @return $this
     */
     public function update($table = null)
     {
         if ($table) {
             $this->table($table);
         }

         $sql = 'UPDATE ' . $this->table . ' SET ';

         $sql .= $this->setFields();

         if ($this->wheres) {
             $sql .= ' WHERE ' . implode(' ' , $this->wheres);
         }

         $this->query($sql, $this->bindings);

         $this->reset();

         return $this;
     }

      /**
      *
      * @return string
      */
     private function setFields()
     {
         $sql = '';

         foreach (array_keys($this->data) as $key) {
             $sql .= '`' . $key . '` = ? , ';
         }

         $sql = rtrim($sql, ', ');

         return $sql;
     }

      /**
      *
      * @return $this
      */
     public function where()
     {
         $bindings = func_get_args();

         $sql = array_shift($bindings);

         $this->addToBindings($bindings);

         $this->wheres[] = $sql;

         return $this;
     }

      /**
      *
      * @return $this
      */
     public function having()
     {
         $bindings = func_get_args();

         $sql = array_shift($bindings);

         $this->addToBindings($bindings);

         $this->havings[] = $sql;

         return $this;
     }

      /**
      *
      * @param array $arguments => PHP 5.6
      * @return $this
      */
     public function groupBy(...$arguments)
     {
         $this->groupBy = $arguments;

         return $this;
     }

     /**
     *
     * @return \PDOStatement
     */
     public function query()
     {
         $bindings = func_get_args();

         $sql = array_shift($bindings);

         if (count($bindings) == 1 AND is_array($bindings[0])) {
             $bindings = $bindings[0];
         }

         try {
             $query = $this->connection()->prepare($sql);

             foreach ($bindings AS $key => $value) {
                 $query->bindValue($key + 1, _e($value));
             }

             $query->execute();

             return $query;
         } catch (PDOException $e) {

             echo $sql;

             pre($this->bindings);

             die($e->getMessage());
         }
     }

      /**
      *
      * @return int
      */
     public function lastId()
     {
         return $this->lastId;
     }

      /**
      *
      * @param mixed $value
      * @return void
      */
     private function addToBindings($value)
     {
         if (is_array($value)) {
             $this->bindings = array_merge($this->bindings, array_values($value));
         } else {
             $this->bindings[] = $value;
         }
     }

      /**
      *
      * @return void
      */
     private function reset()
     {
         $this->limit = null;
         $this->table = null;
         $this->offset = null;
         $this->data = [];
         $this->joins = [];
         $this->wheres = [];
         $this->orerBy = [];
         $this->havings = [];
         $this->groupBy = [];
         $this->selects = [];
         $this->bindings = [];
     }
}