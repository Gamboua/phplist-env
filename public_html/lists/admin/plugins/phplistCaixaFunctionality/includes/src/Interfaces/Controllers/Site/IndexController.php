<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\Site;

use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;

/**
 * Class IndexController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\Site
 */
class IndexController extends AbstractController
{
    public function __invoke()
    {
        echo $this->render('site/index');
    }
}
