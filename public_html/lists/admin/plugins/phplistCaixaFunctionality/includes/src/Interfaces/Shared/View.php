<?php

namespace phplist\Caixa\Functionality\Interfaces\Shared;

/**
 * Class View
 *
 * @package phplist\Caixa\Functionality\Interfaces\Shared
 */
class View
{
    private $view;
    private $params;

    public function __construct($view, array $params = array())
    {
        $this->view = $view;
        $this->params = $params;
    }

    public function render()
    {
        extract($this->params);

        ob_start();
        include(__DIR__ . "/../../Interfaces/views/{$this->view}.php");
        return ob_get_clean();
    }
}
