<?php

namespace Modules\Icomments\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateCommentRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|string|min:1',
            'message' => 'required|string'

        ];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'commentable_type.required' => trans('icomments::comments.messages.type require'),
            'commentable_type.string' => trans('icomments::comments.messages.type is string'),
            'commentable_id.required' => trans('icomments::comments.messages.id require'),
            'commentable_id.min'=>trans('icomments::comments.messages.id min 1'),
            'message.required' => trans('icomments::comments.messages.message is require'),
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
