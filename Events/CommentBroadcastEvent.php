<?php

namespace Modules\Icomments\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Modules\Icomments\Transformers\CommentTransformer;


class CommentBroadcastEvent implements ShouldBroadcastNow
{
  use InteractsWithSockets, SerializesModels;

  public $comment;
  public $type;
  public $id;


  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($type, $id, $comment)
  {
    $this->comment = $comment;
    $this->type = $type;
    $this->id = $id;
    
  }


  public function broadcastWith()
  {
    // This must always be an array. Since it will be parsed with json_encode()
    return [
      "data" => new CommentTransformer($this->comment)
    ];
  }

  public function broadcastAs()
  {
    return $this->type.$this->id;
  }

  /**
   * Get the channels the event should be broadcast on.
   *
   * @return array
   */
  public function broadcastOn()
  {
    return new Channel('global');
  }
}
