<?php

defined('PHPLISTINIT') || die;

use phplist\Caixa\Functionality\Interfaces\Shared\Route;
use phplist\Caixa\Functionality\Interfaces\Controllers\About\IndexController;

$routes = [];
$routes['index'] = new IndexController();

(new Route($routes))->dispatch();
