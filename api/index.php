<?php

/**
 * Pet Adoption
 *
 * @package Pet Adoption
 * @version 1.0.0
 * @api 1.0.0
 * @author Obaydur Rahman <obayed.opu@gmail.com>
 * @copyright 2025 Obaydur Rahman
 */


use Opu\App\Configs\Config;
use Opu\Core\Events;

// disable error reporting and warnings
error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


/**
 *  Define the root directory as BASEPATH
 * @since 1.0.0
 */
define('BASEPATH', __DIR__);

/**
 * Set default timezone
 */
date_default_timezone_set('Asia/Dhaka');

/**
 * Require the autoloader file.
 */
require __DIR__ . '/Vendor/autoload.php';

/**
 * Initialize the event object
 */

$events = new Events();

/**
 * App Initialization
 */
$events->dispatch('app.init');

// single file event listeners
$dirs = scandir(BASEPATH . '/App/Events');
foreach ($dirs as $dir) {
    if ($dir != '.' && $dir != '..') {
        include_once BASEPATH . '/App/Events/' . $dir;
    }
}

/**
 * Cross Domain Access
 */

// Allow from specific origin
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], Config::$allowed_domains)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS, HEAD");
    header("Access-Control-Allow-Headers: Origin, Authorization, X-User, X-Location, X-Requested-With, Content-Type, Accept");
    exit(0);
}

/**
 * Require the routes and initialize the app
 */
require __DIR__ . '/Routes/Routes.php';


$events->dispatch('app.finish');