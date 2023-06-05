<?php
require_once 'model/User.php';
require_once 'controller/SiteController.php';

$route = strip_tags(str_replace('/', '', $_SERVER['REQUEST_URI']));

if ($route === '') {
    $route = 'index';
}

new SiteController($route);

