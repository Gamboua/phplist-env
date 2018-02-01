<?php

defined('PHPLISTINIT') || die;

use phplist\Caixa\Functionality\Interfaces\Controllers\Site\IndexController;
use phplist\Caixa\Functionality\Interfaces\Controllers\Site\TestController;
use phplist\Caixa\Functionality\Interfaces\Controllers\Site\ImportGeneratorController;
use phplist\Caixa\Functionality\Interfaces\Controllers\Site\UserFundsController;
use phplist\Caixa\Functionality\Interfaces\Shared\Route;

$routes = [];
$routes['index'] = new IndexController();
$routes['validapo'] = new TestController();
$routes['userfunds'] = new UserFundsController();
$routes['importgenerator'] = new ImportGeneratorController();

(new Route($routes))->dispatch();
