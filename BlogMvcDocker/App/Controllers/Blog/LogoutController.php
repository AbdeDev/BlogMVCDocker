<?php

namespace App\Controllers\Blog;

use System\Controller;

class LogoutController extends Controller
{
    /**
    *
    * @return mixed
    */
    public function index()
    {
        $this->session->destroy();
        $this->cookie->destroy();

        return $this->url->redirectTo('/');
    }                                   
}