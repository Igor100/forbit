<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

define('ROOT', dirname(__DIR__));
define('CORE_ROOT', ROOT . DIRECTORY_SEPARATOR . 'core');

define('APPLICATION_ROOT', ROOT . DIRECTORY_SEPARATOR . 'application');
define('FILE_CONFIG_DB', DIR_CONFIG . 'db.php');

define('CONFIG_DEBUG', true);
if (file_exists(FILE_CONFIG_DB)) include_once FILE_CONFIG_DB;

include CORE_ROOT . '/router.php';
include CORE_ROOT . '/controller.php';
include CORE_ROOT . '/model.php';
/*include CORE_ROOT . '/extensions/autoload.php';
include CORE_ROOT . '/extensions/crypt.php';
include CORE_ROOT . '/extensions/validator.php';*/
foreach (glob(CORE_ROOT . "/extensions/*.php") as $filename)
    if (file_exists($filename)) include $filename;
foreach (glob(APPLICATION_ROOT . "/models/*.php") as $filename)
    if (file_exists($filename)) include $filename;

