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
        'name' => 'blog',
        'route' => '/admin/blog/',
        'module' => 'Blog',
        'controller' => 'Admin\\Index',
        'action' => 'index',
        'layout' => 'Admin/Default'
    )
);

foreach ($routes as $route) {
    Router::addRoute($route);
}
