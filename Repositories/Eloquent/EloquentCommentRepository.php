<?php

namespace Modules\Icomments\Repositories\Eloquent;

use Modules\Icomments\Repositories\CommentRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentCommentRepository extends EloquentBaseRepository implements CommentRepository
{
    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $model= $this->model->create($data);
        $model->commentable()->associate($data['model']);
        return $model;
    }

}
