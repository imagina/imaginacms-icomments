<?php

namespace Modules\Icomments\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\User\Transformers\UserProfileTransformer;
use Illuminate\Support\Arr;

class CommentTransformer extends Resource
{
  public function toArray($request)
  {
    $data = [
        'id' => $this->when($this->id, $this->id),
        'body' => $this->when($this->body, $this->body),
        'store' => new StoreTransformer($this->whenLoaded('store')),
        'user' => new UserProfileTransformer($this->whenLoaded('user')),
    ];


    return $data;

  }
}
