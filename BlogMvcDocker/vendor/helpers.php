<?php

use System\Application;

if (! function_exists('pre')) {
     /**
     *
     * @param mixed $var
     * @return void
     */
    function pre($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if (! function_exists('pred')) {
     /**
     *
     * @param mixed $var
     * @return void
     */
    function pred($var)
    {
        pre($var);
        die;
    }
}

if (! function_exists('array_get')) {
     /**
     *
     * @param array $array
     * @param string|int $key
     * @param mixed $default
     */
    function array_get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (! function_exists('_e')) {
     /**
     *
     * @param string $value
     * @return string
     */
    function _e($value)
    {
        return htmlspecialchars($value);
    }
}

if (! function_exists('assets')) {
     /**
     *
     * @param string $path
     * @return string
     */
    function assets($path)
    {
        $app = Application::getInstance();

        return $app->url->link('public/' . $path);
    }
}

if (! function_exists('url')) {
     /**
     *
     * @param string $path
     * @return string
     */
    function url($path)
    {
        $app = Application::getInstance();

        return $app->url->link($path);
    }
}

if (! function_exists('read_more')) {
    /**
    *
    * @param string $string
    * @param int $number_of_words
    * @return string
    */
    function read_more($string, $number_of_words)
    {

        $words_of_string = array_filter(explode(' ' , $string));


        if (count($words_of_string) <= $number_of_words) {
            return $string;
        }

        return implode(' ', array_slice($words_of_string, 0, $number_of_words));
    }
}

if (! function_exists('seo')) {
     /**-
     *
     * @param string $string
     * @return string
     */
    function seo($string)
    {

        $string = trim($string);

        $string = preg_replace('#[^\w]#', ' ' , $string);

        $string = preg_replace('#[\s]+#', ' ', $string);

        $string = str_replace(' ', '-', $string);


        return trim(strtolower($string), '-');
    }
}