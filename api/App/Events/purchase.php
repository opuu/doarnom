<?php

namespace Opu\App\Events;

use Opu\Core\Helpers\CRUD;

global $events;

/**
 * Update stock and create expense on purchase creation
 */
$events->addListener('crud.create.purchases', function ($data) {});


/**
 * Update stock and create expense on purchase update
 */
$events->addListener('crud.before_update.purchases', function ($data) {});

$events->addListener('crud.update.purchases', function ($data) use (&$old_data) {});

/**
 * Update stock and create expense on purchase delete
 */
$events->addListener('crud.before_delete.purchases', function ($id) {});