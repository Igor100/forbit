<?php

namespace core;


class Router
{
    public static function Run()
    {
        define('DIR_APP_CONTROLLERS', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'controllers');
        define('DIR_APP_VIEWS', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'views');
        $controller_name = 'main';
        $action_name = 'login';
        $route_array = explode('/', $_SERVER['REQUEST_URI']);
        if (!empty($route_array[1])) {
            $controller_name = strtolower($route_array[1]);
        }
        if (!empty($route_array[2])) {
            $action_name = strtolower($route_array[2]);
        }
        $controller_name = ucfirst($controller_name) . 'Controller';
        $action_name = 'action' . ucfirst($action_name);
        $file_controller = DIR_APP_CONTROLLERS . DIRECTORY_SEPARATOR . $controller_name . '.php';
        if (file_exists($file_controller)) {
            include_once $file_controller;
        } else {
            header('Location: /error/404');
            exit;
        }
        $controller = new $controller_name();
        if (!method_exists($controller, $action_name)) {
            header('Location: /error/404');
            exit;
        }
        $controller->$action_name();
    }
}