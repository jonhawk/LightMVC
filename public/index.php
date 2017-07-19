<?php

// project root (/var/www)
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// application root (/var/www/application)
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);
// public root (/var/www/public)
define('APP_PUB', ROOT . 'public' . DIRECTORY_SEPARATOR);

// autoload
require APP . 'core/autoload.php';

// load config
require APP . 'config/config.php';

// common functions
require APP . 'core/common.php';

// start application
$app = new \core\App();
