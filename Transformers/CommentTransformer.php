<?php

namespace Modules\Icomments\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Iprofile\Transformers\UserTransformer;

use Illuminate\Support\Arr;

class CommentTransformer extends Resource
{
  public function toArray($request)
  {

      $transformerCommentable = config('asgard.icomments.config.transformersCommentable')[$this->commentable_type];

    $data = [
      'id' => $this->when($this->id, $this->id),
      'comment' => $this->when($this->comment, $this->comment),
      'options' => $this->when($this->options, $this->options),
      'user' => new UserTransformer($this->whenLoaded('commenter')),
      'commentable' => new $transformerCommentable($this->commentable),
      'commentableType' => $this->when($this->commentable_type, $this->commentable_type),
      'createdAt' => $this->when($this->created_at, $this->created_at),
      'updatedAt' => $this->when($this->updated_at, $this->updated_at),
    ];


    return $data;

  }
}
