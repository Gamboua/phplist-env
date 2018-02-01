<?php

use phplist\Caixa\Functionality\Interfaces\Shared\Route;
use phplist\Caixa\Functionality\Interfaces\Controllers\ClientsNoEmail\IndexController;
use phplist\Caixa\Functionality\Interfaces\Controllers\ClientsNoEmail\FilterController;
use phplist\Caixa\Functionality\Interfaces\Controllers\ClientsNoEmail\ExportController;
defined('PHPLISTINIT') || die;


$routes = [];
$routes['index'] = new IndexController();
$routes['filter'] = new FilterController();
$routes['exportCSV'] = new ExportController();

(new Route($routes))->dispatch();
