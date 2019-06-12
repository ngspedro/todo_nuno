<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/* Private Routes (Logged in user required) */
Route::middleware('auth:api')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tasks', 'TaskController@index');
    Route::post('tasks', 'TaskController@create');
    Route::put('tasks/{id}', 'TaskController@edit');
    Route::delete('tasks/{id}', 'TaskController@delete');
    
    Route::get('priorities', 'PriorityController@index');
    
    Route::post('/logout', 'AuthController@logout');
});

/* Public Routes (Not required to be logged in) */
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');