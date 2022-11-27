<?php

namespace System;

class Html
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
    private $title;

     /**
     *
     * @var string
     */
    private $description;

     /**
     *
     * @var string
     */
    private $keywords;

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
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

     /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

     /**
     *
     * @param string $keywords
     * @return void
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

     /**
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

     /**
     *
     * @param string $description
     * @return void
     */
    public function setDecription($description)
    {
        $this->description = $description;
    }

     /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}