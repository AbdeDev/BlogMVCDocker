<?php

namespace System;

class File
{
     /**
     *
     * @const string
     */
    const DS = DIRECTORY_SEPARATOR;

     /**
     *
     * @var string
     */
    private $root;

     /**
     *
     * @param string $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

     /**
     *
     * @param string $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($this->to($file));
    }

     /**
     *
     * @param string $file
     * @return mixed
     */
    public function call($file)
    {
        return require $this->to($file);
    }

     /**
     *
     * @param string $path
     * @return string
     */
    public function toVendor($path)
    {
        return $this->to('vendor/' . $path);
    }

     /**
     *
     * @param string $path
     * @return string
     */
    public function toPublic($path)
    {
        return $this->to('public/' . $path);
    }

   /**
   *
   * @param string $path
   * @return string
   */
  public function to($path)
  {
      return $this->root . static::DS . str_replace(['/', '\\'], static::DS, $path);
  }
}