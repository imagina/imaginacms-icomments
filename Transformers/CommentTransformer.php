<?php

namespace Modules\Icomments\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;

class CommentTransformer extends CrudResource
{
  /**
  * Method to merge values with response
  *
  * @return array
  */
  public function modelAttributes($request)
  {
    return [];
  }
}
