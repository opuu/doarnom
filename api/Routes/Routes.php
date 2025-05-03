<?php

/**
 * App routes
 * @since 1.0.0
 */

use Opu\App\Configs\Config;
use Opu\App\Controllers\AuthController;
use Opu\App\Controllers\UserController;
use Opu\App\Controllers\CategoryController;
use Opu\App\Controllers\BreedController;
use Opu\App\Controllers\TraitController;
use Opu\App\Controllers\ShelterController;
use Opu\App\Controllers\PetController;
use Opu\App\Controllers\AdoptionController;
use Opu\App\Models\AuthModel;
use Opu\Core\Controller;
use Opu\Core\Start;

/**
 * Start the app
 */
$app = new Start();

// sign in
$app->router->add('post', '/auth/signin', [new AuthController(), 'signin']);

// sign up
$app->router->add('post', '/auth/signup', [new AuthController(), 'signup']);

// if not authorized, send 401 response
$allowed = AuthModel::is_authorized();

if ($allowed === 'no_permission') {
    return Controller::send(403, null, 'Your request was denied.');
} else if (!$allowed) {
    /**
     * Listen for client pages
     */
    try {
        $app->router->run();
    } catch (\Throwable $th) {
        if (Config::$app_debug) {
            throw $th;
        } else {
            Controller::send(500, null, 'Something went wrong.');
        }
        exit;
    }

    return Controller::send(401, null, 'Unauthorized');
}

// sign out
$app->router->add('delete', '/auth/signout', [new AuthController(), 'signout']);

// users
$app->router->add('get', '/users', [new UserController(), 'index']);
$app->router->add('get', '/users/:num', [new UserController(), 'single']);
$app->router->add('post', '/users', [new UserController(), 'create']);
$app->router->add('patch', '/users/:num', [new UserController(), 'update']);
$app->router->add('delete', '/users/:num', [new UserController(), 'delete']);

// categories
$app->router->add('get', '/categories', [new CategoryController(), 'index']);
$app->router->add('get', '/categories/:num', [new CategoryController(), 'single']);
$app->router->add('post', '/categories', [new CategoryController(), 'create']);
$app->router->add('patch', '/categories/:num', [new CategoryController(), 'update']);
$app->router->add('delete', '/categories/:num', [new CategoryController(), 'delete']);

// breeds
$app->router->add('get', '/breeds', [new BreedController(), 'index']);
$app->router->add('get', '/breeds/:num', [new BreedController(), 'single']);
$app->router->add('post', '/breeds', [new BreedController(), 'create']);
$app->router->add('patch', '/breeds/:num', [new BreedController(), 'update']);
$app->router->add('delete', '/breeds/:num', [new BreedController(), 'delete']);

// traits
$app->router->add('get', '/traits', [new TraitController(), 'index']);
$app->router->add('get', '/traits/:num', [new TraitController(), 'single']);
$app->router->add('post', '/traits', [new TraitController(), 'create']);
$app->router->add('patch', '/traits/:num', [new TraitController(), 'update']);
$app->router->add('delete', '/traits/:num', [new TraitController(), 'delete']);

// shelters
$app->router->add('get', '/shelters', [new ShelterController(), 'index']);
$app->router->add('get', '/shelters/:num', [new ShelterController(), 'single']);
$app->router->add('post', '/shelters', [new ShelterController(), 'create']);
$app->router->add('patch', '/shelters/:num', [new ShelterController(), 'update']);
$app->router->add('delete', '/shelters/:num', [new ShelterController(), 'delete']);

// pets
$app->router->add('get', '/pets', [new PetController(), 'index']);
$app->router->add('get', '/pets/:num', [new PetController(), 'single']);
$app->router->add('post', '/pets', [new PetController(), 'create']);
$app->router->add('patch', '/pets/:num', [new PetController(), 'update']);
$app->router->add('delete', '/pets/:num', [new PetController(), 'delete']);

// adoptions
$app->router->add('get', '/adoptions', [new AdoptionController(), 'index']);
$app->router->add('get', '/adoptions/:num', [new AdoptionController(), 'single']);
$app->router->add('post', '/adoptions', [new AdoptionController(), 'create']);
$app->router->add('patch', '/adoptions/:num', [new AdoptionController(), 'update']);
$app->router->add('delete', '/adoptions/:num', [new AdoptionController(), 'delete']);

// last route is 404 response as all others failed.
$app->router->add(strtolower($_SERVER['REQUEST_METHOD']), '/:any/:any/:any', function () {
    Controller::send(404, null, 'API endpoint not found');
});

/**
 * Listen for client pages
 */
try {
    $app->router->run();
} catch (\Throwable $th) {
    if (Config::$app_debug) {
        throw $th;
    } else {
        Controller::send(500, null, 'Something went wrong.');
    };
    exit;
}