<?php

namespace System;

class Pagination
{
     /**
     *
     * @var \System\Application
     */
    private $app;

     /**
     *
     * @var int
     */
    private $totalItems;

     /**
     *
     * @var int
     */
    private $itemsPerPage = 10;

     /**
     *
     * @var int
     */
    private $lastPage;

     /**
     *
     * @var int
     */
    private $page = 1;

     /**
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->setCurrentPage();
    }

     /**
     *
     * @return void
     */
    private function setCurrentPage()
    {
        $page = $this->app->request->get('page');

        if (! is_numeric($page) OR $page < 1) {
            $page = 1;
        }

        $this->page = $page;
    }

     /**
     *
     * @return int
     */
    public function page()
    {
        return $this->page;
    }

     /**
     *
     * @return int
     */
    public function itemsPerPage()
    {
        return $this->itemsPerPage;
    }

     /**
     *
     * @return int
     */
    public function totalItems()
    {
        return $this->totalItems;
    }

     /**
     *
     * @return int
     */
    public function last()
    {
        return $this->lastPage;
    }

     /**
     *
     * @return int
     */
    public function next()
    {
        return $this->page + 1;
    }

     /**
     *
     * @return int
     */
    public function prev()
    {
        return $this->page - 1;
    }

     /**
     *
     * @param int $totalItems
     * @return $this
     */
    public function setTotalItems($totalItems)
    {
        $this->totalItems = $totalItems;

        return $this;
    }

     /**
     *
     * @param int $itemsPerPage
     * @return $this
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

     /**
     *
     * @return $this
     */
    public function paginate()
    {
        $this->setLastPage();

        return $this;
    }

     /**
     *
     * @return void
     */
    private function setLastPage()
    {
        $this->lastPage = ceil($this->totalItems / $this->itemsPerPage);
    }

}