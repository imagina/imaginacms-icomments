<?php

namespace Modules\Icomments\Entities;

use Modules\Core\Icrud\Entities\CrudModel;
use Modules\Media\Support\Traits\MediaRelation;

class Comment extends CrudModel
{
    
    use MediaRelation;

    protected $table = 'icomments__comments';
    public $transformer = 'Modules\Icomments\Transformers\CommentTransformer';
    public $requestValidation = [
        'create' => 'Modules\Icomments\Http\Requests\CreateCommentRequest',
        'update' => 'Modules\Icomments\Http\Requests\UpdateCommentRequest',
      ];
    
     protected $fillable = [
        'comment',
        'approved',
        'guest_name',
        'commentable_type',
        'guest_email',
        'user_id',
        'options'
    ];

    protected $with = ['commenter', 'commentable'];
    protected $casts = [
        'approved' => 'boolean',
        'options' => 'array'
    ];
    protected $fakeColumns = ['options'];

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

    public function user()
    {
        $driver = config('asgard.user.config.driver');

        return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
    }

    public function getOptionsAttribute($value)
    {
        return json_decode($value);
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value);
    }

}
