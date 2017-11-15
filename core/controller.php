<?php

namespace core;


class Controller
{
    public $layout = 'default';

    public function render($view, $data = [])
    {
        $controllerName = strtolower(str_replace('Controller', '', get_class($this)));
        $view = DIR_APP_VIEWS . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . $view . '.php';
        include_once DIR_APP_VIEWS . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $this->layout . '.php';
    }
}