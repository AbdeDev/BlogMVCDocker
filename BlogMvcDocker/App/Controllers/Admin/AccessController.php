<?php

namespace App\Controllers\Admin;

use System\Controller;

class AccessController extends Controller
{
    /**
    *
    * @return void
    */
    public function index()
    {
        $loginModel = $this->load->model('Login');

        $ignoredPages = ['/admin/login', '/admin/login/submit'];
                         
        $currentRoute = $this->route->getCurrentRouteUrl();

        if (($isNotLogged =  ! $loginModel->isLogged()) AND ! in_array($currentRoute , $ignoredPages)) {
            return $this->url->redirectTo('/admin/login');
        }

        if ($isNotLogged) {
            return false;
        }

        $user = $loginModel->user();

        $usersGroupsModel = $this->load->model('UsersGroups');

        $usersGroup = $usersGroupsModel->get($user->users_group_id);


        if (! in_array($currentRoute, $usersGroup->pages)) {

            return $this->url->redirectTo('/404');
        }
    }
}