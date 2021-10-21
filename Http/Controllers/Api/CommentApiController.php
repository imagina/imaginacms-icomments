<?php

namespace Modules\Icomments\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model
use Modules\Icomments\Entities\Comment;
use Modules\Icomments\Repositories\CommentRepository;

class CommentApiController extends BaseCrudController
{
  public $model;
  public $modelRepository;

  public function __construct(Comment $model, CommentRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }
}
