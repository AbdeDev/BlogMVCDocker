<?php


use System\Application;

$app = Application::getInstance();

if (strpos($app->request->url(), '/admin') === 0 ) {

    $app->route->callFirst(function ($app) {
        $app->load->action('Admin/Access', 'index');
    });

    $app->share('adminLayout', function ($app) {
        return $app->load->controller('Admin/Common/Layout');
    });
} else {
    $app->share('blogLayout', function ($app) {
        return $app->load->controller('Blog/Common/Layout');
    });


    $app->share('settings', function ($app) {
        $settingsModel = $app->load->model('Settings');

        $settingsModel->loadAll();

        return $settingsModel;
    });
}

$app->route->add('/admin/login', 'Admin/Login');
$app->route->add('/admin/login/submit', 'Admin/Login@submit', 'POST');

$app->route->add('/admin' , 'Admin/Dashboard');
$app->route->add('/admin/dashboard' , 'Admin/Dashboard');
$app->route->add('/admin/submit' , 'Admin/Dashboard@submit', 'POST');

$app->route->add('/admin/users', 'Admin/Users');
$app->route->add('/admin/users/add', 'Admin/Users@add', 'POST');
$app->route->add('/admin/users/submit', 'Admin/Users@submit', 'POST');
$app->route->add('/admin/users/edit/:id', 'Admin/Users@edit', 'POST');
$app->route->add('/admin/users/save/:id', 'Admin/Users@save' , 'POST');
$app->route->add('/admin/users/delete/:id', 'Admin/Users@delete', 'POST');

$app->route->add('/admin/profile/update', 'Admin/Profile@update' , 'POST');

$app->route->add('/admin/users-groups', 'Admin/UsersGroups');
$app->route->add('/admin/users-groups/add', 'Admin/UsersGroups@add', 'POST');
$app->route->add('/admin/users-groups/submit', 'Admin/UsersGroups@submit', 'POST');
$app->route->add('/admin/users-groups/edit/:id', 'Admin/UsersGroups@edit', 'POST');
$app->route->add('/admin/users-groups/save/:id', 'Admin/UsersGroups@save' , 'POST');
$app->route->add('/admin/users-groups/delete/:id', 'Admin/UsersGroups@delete', 'POST');

$app->route->add('/admin/posts', 'Admin/Posts');
$app->route->add('/admin/posts/add', 'Admin/Posts@add', 'POST');
$app->route->add('/admin/posts/submit', 'Admin/Posts@submit', 'POST');
$app->route->add('/admin/posts/edit/:id', 'Admin/Posts@edit', 'POST');
$app->route->add('/admin/posts/save/:id', 'Admin/Posts@save' , 'POST');
$app->route->add('/admin/posts/delete/:id', 'Admin/Posts@delete', 'POST');

$app->route->add('/admin/categories', 'Admin/Categories');
$app->route->add('/admin/categories/add', 'Admin/Categories@add', 'POST');
$app->route->add('/admin/categories/submit', 'Admin/Categories@submit', 'POST');

$app->route->add('/admin/categories/edit/:id', 'Admin/Categories@edit', 'POST');
$app->route->add('/admin/categories/save/:id', 'Admin/Categories@save' , 'POST');
$app->route->add('/admin/categories/delete/:id', 'Admin/Categories@delete', 'POST');

$app->route->add('/admin/settings', 'Admin/Settings');
$app->route->add('/admin/settings/save', 'Admin/Settings@save', 'POST');

$app->route->add('/admin/contacts', 'Admin/Contacts');
$app->route->add('/admin/contacts/reply/:id', 'Admin/Contacts@reply');
$app->route->add('/admin/contacts/send/:id', 'Admin/Contacts@send' , 'POST');

$app->route->add('/admin/ads', 'Admin/Ads');
$app->route->add('/admin/ads/add', 'Admin/Ads@add', 'POST');
$app->route->add('/admin/ads/submit', 'Admin/Ads@submit', 'POST');
$app->route->add('/admin/ads/edit/:id', 'Admin/Ads@edit', 'POST');
$app->route->add('/admin/ads/save/:id', 'Admin/Ads@save' , 'POST');
$app->route->add('/admin/ads/delete/:id', 'Admin/Ads@delete', 'POST');

$app->route->add('/admin/logout', 'Admin/Logout');


$app->route->add('/', 'Blog/Home'); // Home Page
$app->route->add('/category/:text/:id', 'Blog/Category');
$app->route->add('/post/:text/:id', 'Blog/Post');
$app->route->add('/post/:text/:id/add-comment', 'Blog/Post@addComment', 'POST');
$app->route->add('/register', 'Blog/Register');
$app->route->add('/register/submit', 'Blog/Register@submit', 'POST');
$app->route->add('/login', 'Blog/Login');
$app->route->add('/login/submit', 'Blog/Login@submit', 'POST');
$app->route->add('/logout', 'Blog/Logout');

$app->route->add('/404', 'NotFound');
$app->route->notFound('/404');