<?php

namespace Modules\Icomments\Entities;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $table = 'icomments__comments';
    public $translatedAttributes = [];
    protected $fillable = [
        'comment',
        'approved',
        'guest_name',
      'commentable_type',
        'guest_email',
        'options'
    ];
    protected $with = ['commenter','commentable'];
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


    public function getOptionsAttribute($value){
        $options = json_decode($value);

        if(isset($options->mainImage))
            $options->mainImage = url($options->mainImage);
        if(isset($options->secondaryImage))
            $options->secondaryImage = url($options->secondaryImage);

        return $options;
    }

    public function setOptionsAttribute($value){
        $this->attributes['options'] = json_encode($value);
    }


}
