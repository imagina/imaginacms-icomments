<?php

namespace Modules\Icommens\Events;


use Modules\Media\Contracts\StoringMedia;

class CommentWasUpdated implements StoringMedia
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var Comment
     */
    public $comment;

    public function __construct(Comment $comment, array $data)
    {
        $this->data = $data;
        $this->comment = $comment;
    }

    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity()
    {
        return $this->comment;
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
