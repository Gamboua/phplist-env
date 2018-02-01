<?php

use phplist\Caixa\Functionality\Interfaces\Controllers\AnalyticalReport\IndexController;
use phplist\Caixa\Functionality\Interfaces\Controllers\AnalyticalReport\ViewController;
use phplist\Caixa\Functionality\Interfaces\Shared\Route;

defined('PHPLISTINIT') || die;

$routes = [];
$routes['index'] = new IndexController();
$routes['view'] = new ViewController();

(new Route($routes))->dispatch();
