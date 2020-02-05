<?php

namespace Modules\Icomments\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Icomments\Entities\Comment;
use Modules\Icomments\Http\Requests\CreateCommentRequest;
use Modules\Icomments\Http\Requests\UpdateCommentRequest;
use Modules\Icomments\Repositories\CommentRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class CommentController extends AdminBaseController
{
    /**
     * @var CommentRepository
     */
    private $comment;

    public function __construct(CommentRepository $comment)
    {
        parent::__construct();

        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$comments = $this->comment->all();

        return view('icomments::admin.comments.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('icomments::admin.comments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCommentRequest $request
     * @return Response
     */
    public function store(CreateCommentRequest $request)
    {
        $this->comment->create($request->all());

        return redirect()->route('admin.icomments.comment.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('icomments::comments.title.comments')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Comment $comment
     * @return Response
     */
    public function edit(Comment $comment)
    {
        return view('icomments::admin.comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Comment $comment
     * @param  UpdateCommentRequest $request
     * @return Response
     */
    public function update(Comment $comment, UpdateCommentRequest $request)
    {
        $this->comment->update($comment, $request->all());

        return redirect()->route('admin.icomments.comment.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('icomments::comments.title.comments')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Comment $comment
     * @return Response
     */
    public function destroy(Comment $comment)
    {
        $this->comment->destroy($comment);

        return redirect()->route('admin.icomments.comment.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('icomments::comments.title.comments')]));
    }
}
