<?php

namespace System\View;

interface ViewInterface
{
     /**
     *
     * @return string
     */
    public function getOutput();

     /**
     *
     * @return string
     */
    public function __toString();
}