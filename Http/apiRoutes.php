<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => 'icomments/v1'], function (Router $router) {
    $router->group(['prefix' => '/comments'], function (Router $router) {
        //Route create
        $router->post('/', [
            'as' => 'icomments.comment.create',
            'uses' => 'CommentApiController@create',
            'middleware' => ['auth:api']
        ]);

        //Route index
        $router->get('/', [
            'as' => 'icomments.comment.get.items.by',
            'uses' => 'CommentApiController@index',
            'middleware' => ['auth:api']
        ]);

        //Route show
        $router->get('/{criteria}', [
            'as' => 'icomments.comment.get.item',
            'uses' => 'CommentApiController@show',
            'middleware' => ['auth:api']
        ]);

        //Route update
        $router->put('/{criteria}', [
            'as' => 'icomments.comment.update',
            'uses' => 'CommentApiController@update',
            'middleware' => ['auth:api']
        ]);

        //Route delete
        $router->delete('/{criteria}', [
            'as' => 'icomments.comment.delete',
            'uses' => 'CommentApiController@delete',
            'middleware' => ['auth:api']
        ]);
    });
});