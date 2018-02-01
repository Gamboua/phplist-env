<?php

defined('PHPLISTINIT') || die;

use phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall\CreateController;
use phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall\EditController;
use phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall\IndexController;
use phplist\Caixa\Functionality\Interfaces\Shared\Route;

$routes = [];
$routes['index'] = new IndexController();
$routes['create'] = new CreateController();
$routes['edit'] = new EditController();

(new Route($routes))->dispatch();
