<?php
declare(strict_types=1);

use Cake\Core\ClassLoader;
use Cake\Core\Configure;

// use Cake\Datasource\ConnectionManager;

require_once 'vendor/autoload.php';

// Path constants to a few helpful things.
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('ROOT', dirname(__DIR__));
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TESTS', ROOT . DS . 'tests');
define('APP', ROOT . DS . 'tests' . DS . 'test_app' . DS);
define('APP_DIR', 'test_app');
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', APP . 'webroot' . DS);
define('TMP', sys_get_temp_dir() . DS);
define('CONFIG', APP . 'config' . DS);
define('CACHE', TMP);
define('LOGS', TMP);

$loader = new ClassLoader();
$loader->register();

$loader->addNamespace('TestApp', APP);

require_once CORE_PATH . 'config/bootstrap.php';

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

Configure::write('Session', [
    'defaults' => 'php',
]);

Configure::write('Contact.addressFormat', ":organization\n:street1\n:street2\n:postalCode :locality\n:country");
