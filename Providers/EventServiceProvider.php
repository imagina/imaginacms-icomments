<?php

namespace Modules\Icomments\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Icomments\Events\CommentWasCreated;
use Modules\Icomments\Events\Handlers\CommentBroadcast;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CommentWasCreated::class => [
           CommentBroadcast::class,
        ],
   

    ];
}