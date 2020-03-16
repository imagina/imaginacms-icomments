<?php
namespace Modules\Icomments\Events\Handlers;



use Modules\Icomments\Events\CommentBroadcastEvent;
use Modules\Icomments\Events\CommentWasCreated;

class CommentBroadcast
{

    public function handle(CommentWasCreated $event)
    {
      
      $availableEntities = config('asgard.icomments.config.availableEntities');
  
  
      if(in_array($event->type, $availableEntities)){
            event(new CommentBroadcastEvent( $event->type, $event->id,  $event->comment));
        }
    }

}