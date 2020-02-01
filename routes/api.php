<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group(['prefix'=>'api'], function() use ($router){
    $router->group(['prefix'=>'clients'], function() use ($router){
        $router->post('/', 'ClientController@save');
        $router->get('/', 'ClientController@get');
        $router->get('/{id}', 'ClientController@getById');
        $router->put('/{id}', 'ClientController@update');
        $router->patch('/{id}', 'ClientController@patch');
        $router->delete('/{id}', 'ClientController@delete');
    });

    $router->group(['prefix'=>'address'], function() use ($router){
        $router->post('/', 'AddressController@save');
        $router->get('/', 'AddressController@get');
        $router->get('/{id}', 'AddressController@getById');
        $router->put('/{id}', 'AddressController@update');
        $router->patch('/{id}', 'AddressController@patch');
        $router->delete('/{id}', 'AddressController@delete');
    });

    $router->group(['prefix'=>'debts'], function() use ($router){
        $router->post('/', 'DebtController@save');
        $router->get('/', 'DebtController@get');
        $router->get('/{id}', 'DebtController@getById');
        $router->put('/{id}', 'DebtController@update');
        $router->patch('/{id}', 'DebtController@patch');
        $router->delete('/{id}', 'DebtController@delete');
    });
});
