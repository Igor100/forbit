<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

define('ROOT', dirname(__DIR__));
define('CORE_ROOT', ROOT . DIRECTORY_SEPARATOR . 'core');

define('APPLICATION_ROOT', ROOT . DIRECTORY_SEPARATOR . 'application');
define('FILE_CONFIG_DB', DIR_CONFIG . 'db.php');

define('CONFIG_DEBUG', true);
if (file_exists(FILE_CONFIG_DB)) include_once FILE_CONFIG_DB;

include_once CORE_ROOT . '/router.php';
include_once CORE_ROOT . '/controller.php';
include_once CORE_ROOT . '/model.php';

foreach (glob(CORE_ROOT . "/extensions/*.php") as $filename)
    if (file_exists($filename)) include_once $filename;
foreach (glob(APPLICATION_ROOT . "/models/*.php") as $filename)
    if (file_exists($filename)) include_once $filename;

