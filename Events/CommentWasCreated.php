<?php

namespace Modules\Icomments\Events;


use Modules\Icomments\Entities\Comment;
use Modules\Media\Contracts\StoringMedia;

class CommentWasCreated implements StoringMedia
{
    /**
     * @var array
     */
    public $comment;
    public $type;
    public $id;


    public function __construct($type,$id,$comment)
    {
        $this->comment = $comment;
        $this->type = $type;
        $this->id = $id;
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
        return $this->comment;
    }
}
