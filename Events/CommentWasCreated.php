<?php

namespace Modules\Icomments\Events;


use Modules\Icomments\Entities\Comment;
use Modules\Media\Contracts\StoringMedia;

class CommentWasCreated implements StoringMedia
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var Comment
     */
    public $entity;

    public function __construct($category, array $data)
    {
        $this->data = $data;
        $this->entity = $category;
    }

    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData()
    {
        return $this->data;
    }
}
