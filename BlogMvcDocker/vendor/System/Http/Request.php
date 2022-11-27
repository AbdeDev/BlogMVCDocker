<?php

namespace System\Http;

class Request
{
     /**
     *
     * @var string
     */
    private $url;

     /**
     *
     * @var string
     */
    private $baseUrl;

     /**
     *
     * @var array
     */
    private $files = [];

     /**
     *
     * @return void
     */
    public function prepareUrl()
    {
        $script = dirname($this->server('SCRIPT_NAME'));

        $requestUri = $this->server('REQUEST_URI');

        if (strpos($requestUri, '?') !== false) {
            list($requestUri, $queryString) = explode('?' , $requestUri);
        }

        $this->url = rtrim(preg_replace('#^'.$script.'#', '' , $requestUri), '/');

        if (! $this->url) {
            $this->url = '/';
        }

        $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script . '/';
    }

     /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = array_get($_GET, $key, $default);

        if (is_array($value)) {
            $value = array_filter($value);
        } else {
            $value = trim($value);
        }

        return $value;
    }

     /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = null)
    {
        $value = array_get($_POST, $key, $default);
        if (is_array($value)) {
            $value = array_filter($value);
        } else {
            $value = trim($value);
        }

        return $value;
    }

     /**
     *
     * @param string $key
     * @param mixed $valuet
     * @return mixed
     */
    public function setPost($key, $value)
    {
        $_POST[$key] = $value;
    }

     /**
     *
     * @param string $input
     * @return \System\Http\UploadedFile
     */
    public function file($input)
    {
        if (isset($this->files[$input])) {
            return $this->files[$input];
        }

        $uploadedFile = new UploadedFile($input);

        $this->files[$input] = $uploadedFile;

        return $this->files[$input];
    }

     /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function server($key, $default = null)
    {
        return array_get($_SERVER, $key, $default);
    }

     /**
     *
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

     /**
     *
     * @return string
     */
    public function referer()
    {
        return $this->server('HTTP_REFERER');
    }

     /**
     *
     * @return string
     */
    public function baseUrl()
    {
        return $this->baseUrl;
    }

     /**
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }
}