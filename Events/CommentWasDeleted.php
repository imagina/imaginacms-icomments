<?php

namespace Modules\Marketplace\Events;

use Modules\Icomments\Entities\Comment;
use Modules\Media\Contracts\DeletingMedia;

class CommentWasDeleted implements DeletingMedia
{

    /**
     * @var Comment
     */
    public $commentClass;

    /**
     * @var commentId
     */
    public $commentId;

    public function __construct($commentId,$commentClass)
    {
        $this->commentClass = $commentClass;
        $this->commentId = $commentId;
    }

    /**
     * Get the entity ID
     * @return int
     */
    public function getEntityId()
    {
        return $this->commentId;
    }

    /**
     * Get the class name the imageables
     * @return string
     */
    public function getClassName()
    {
        return $this->commentClass;
    }
}
