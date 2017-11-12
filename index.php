<?php

use core\Router;

define('DIR_CONFIG', __DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR);
header("Content-Type: text/html; charset=utf-8");
session_start();
include DIR_CONFIG.'main.php';

Router::run();
