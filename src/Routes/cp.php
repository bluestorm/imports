<?php

Route::get('imports', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@index', 'as' => 'imports.index']);

Route::get('imports/new', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@create', 'as' => 'imports.new']);
Route::post('imports/new', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@store', 'as' => 'imports.store']);

Route::get('imports/{import}/map', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@mapFields', 'as' => 'imports.map']);
Route::post('imports/{import}/map', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@storeMappedFields', 'as' => 'imports.storeMappedFields']);

Route::get('imports/{import}/edit', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@edit', 'as' => 'imports.edit']);
Route::post('imports/{import}/edit', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@update', 'as' => 'imports.update']);

Route::get('imports/{import}/import', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@import', 'as' => 'imports.import']);

Route::get('imports/{import}/destroy', ['uses' => '\BlueStorm\Imports\Controllers\ImportsCPController@destroy', 'as' => 'imports.destroy']);
