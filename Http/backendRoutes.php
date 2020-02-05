<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/icomments'], function (Router $router) {
    $router->bind('comment', function ($id) {
        return app('Modules\Icomments\Repositories\CommentRepository')->find($id);
    });
    $router->get('comments', [
        'as' => 'admin.icomments.comment.index',
        'uses' => 'CommentController@index',
        'middleware' => 'can:icomments.comments.index'
    ]);
    $router->get('comments/create', [
        'as' => 'admin.icomments.comment.create',
        'uses' => 'CommentController@create',
        'middleware' => 'can:icomments.comments.create'
    ]);
    $router->post('comments', [
        'as' => 'admin.icomments.comment.store',
        'uses' => 'CommentController@store',
        'middleware' => 'can:icomments.comments.create'
    ]);
    $router->get('comments/{comment}/edit', [
        'as' => 'admin.icomments.comment.edit',
        'uses' => 'CommentController@edit',
        'middleware' => 'can:icomments.comments.edit'
    ]);
    $router->put('comments/{comment}', [
        'as' => 'admin.icomments.comment.update',
        'uses' => 'CommentController@update',
        'middleware' => 'can:icomments.comments.edit'
    ]);
    $router->delete('comments/{comment}', [
        'as' => 'admin.icomments.comment.destroy',
        'uses' => 'CommentController@destroy',
        'middleware' => 'can:icomments.comments.destroy'
    ]);
// append

});
