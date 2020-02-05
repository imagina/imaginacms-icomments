<?php

namespace Modules\Icomments\Entities;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $table = 'icomments__comments';
    public $translatedAttributes = [];
    protected $fillable = ['comment', 'approved', 'guest_name', 'guest_email'];
    protected $with = ['commenter'];
    protected $casts = [
        'approved' => 'boolean'
    ];


    public function commenter()
    {
        return $this->morphTo();
    }

    /**
     * The model that was commented upon.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Returns all comments that this comment is the parent of.
     */
    public function children()
    {
        return $this->hasMany(Comment::class, 'child_id');
    }

    /**
     * Returns the comment to which this comment belongs to.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'child_id');
    }

}
