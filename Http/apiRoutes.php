<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => 'icomments/v1','middleware' => ['auth:api']], function (Router $router) {
    $router->group(['prefix' => '/comments'], function (Router $router) {
        //Route create
        $router->post('/', [
            'as' => 'icomments.comment.create',
            'uses' => 'CommentApiController@create',
            'middleware' => 'auth-can:icomments.comments.create'
            
        ]);

        //Route index
        $router->get('/', [
            'as' => 'icomments.comment.get.items.by',
            'uses' => 'CommentApiController@index',
            'middleware' => 'auth-can:icomments.comments.index'
        ]);

        //Route show
        $router->get('/{criteria}', [
            'as' => 'icomments.comment.get.item',
            'uses' => 'CommentApiController@show',
            'middleware' => 'auth-can:icomments.comments.show'
        ]);

        //Route update
        $router->put('/{criteria}', [
            'as' => 'icomments.comment.update',
            'uses' => 'CommentApiController@update',
          'middleware' => 'auth-can:icomments.comments.edit'
        ]);

        //Route delete
        $router->delete('/{criteria}', [
            'as' => 'icomments.comment.delete',
            'uses' => 'CommentApiController@delete',
            'middleware' => 'auth-can:icomments.comments.destroy'
        ]);
    });
});