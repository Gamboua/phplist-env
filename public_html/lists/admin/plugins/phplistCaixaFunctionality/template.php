<?php

defined('PHPLISTINIT') || die;

use phplist\Caixa\Functionality\Interfaces\Controllers\Template\IndexController;
use phplist\Caixa\Functionality\Interfaces\Shared\Route;

$routes = [];
$routes['index'] = new IndexController();

(new Route($routes))->dispatch();
