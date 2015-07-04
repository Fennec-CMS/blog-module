<?php
use Fennec\Library\Router;

$routes = array(
    array(
        'name' => 'blog-base',
        'route' => '/blog/',
        'module' => 'Blog',
        'controller' => 'Index',
        'action' => 'index',
        'layout' => 'Default'
    ),
    array(
        'name' => 'blog-page',
        'route' => '/blog/page/([0-9]+)/',
        'params' => array(
            'page'
        ),
        'module' => 'Blog',
        'controller' => 'Index',
        'action' => 'index',
        'layout' => 'Default'
    ),
    array(
        'name' => 'blog-read',
        'route' => '/blog/([a-zA-Z0-9+-]+)/',
        'params' => array(
            'slug'
        ),
        'module' => 'Blog',
        'controller' => 'Index',
        'action' => 'read',
        'layout' => 'Default'
    ),
    array(
        'name' => 'admin-blog-list',
        'route' => '/admin/blog/',
        'module' => 'Blog',
        'controller' => 'Admin\\Index',
        'action' => 'index',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-blog-write',
        'route' => '/admin/blog/write/',
        'module' => 'Blog',
        'controller' => 'Admin\\Index',
        'action' => 'write',
        'layout' => 'Admin/Default'
    ),
    array(
        'name' => 'admin-blog-edit',
        'route' => '/admin/blog/edit/([0-9]+)/',
        'params' => array(
            'id'
        ),
        'module' => 'Blog',
        'controller' => 'Admin\\Index',
        'action' => 'write',
        'layout' => 'Admin/Default'
    )
);

foreach ($routes as $route) {
    Router::addRoute($route);
}
