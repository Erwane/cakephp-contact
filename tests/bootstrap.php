<?php
declare(strict_types=1);

use Cake\Core\Configure;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Path constants to a few helpful things.
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('ROOT', dirname(__DIR__));
const CORE_PATH = ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS;
const CAKE = CORE_PATH . 'src' . DS;

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

Configure::write('debug', true);
Configure::write('App.encoding', 'UTF-8');
