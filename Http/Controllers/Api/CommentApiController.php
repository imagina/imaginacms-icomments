<?php

namespace Modules\Icomments\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model
use Modules\Icomments\Entities\Comment;
use Modules\Icomments\Repositories\CommentRepository;

use Modules\Icomments\Transformers\CommentTransformer;

class CommentApiController extends BaseCrudController
{
  public $model;
  public $modelRepository;

  public function __construct(Comment $model, CommentRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }

  /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */

    public function create(Request $request)
    {
        \DB::beginTransaction();
        try {
            $data = $request->input('attributes') ?? [];//Get data  
            
            //Validate Request
            if (isset($this->model->requestValidation['create'])) {
              $this->validateRequestApi(new $this->model->requestValidation['create']($data));
            }

            $commentable = $data['commentable_type']::find($data["commentable_id"]);

            $data['approved']=!config('comments.approval_required');

            //Break if no found item
            if (!$commentable) throw new \Exception('Commentable type not found', 404);

            $data["commentable"] = $commentable;
            $data["commenter"] = $params->user;
            $dataEntity = $this->modelRepository->create($data);

          
            //Response
            $response = ["data" => new CommentTransformer($dataEntity)];
            \DB::commit(); //Commit to Data Base

            $dataEntity->commentable()->associate($data['commentable']);
            $dataEntity->commenter()->associate($data['commenter']);
            $dataEntity->save();
            
        } catch (\Exception $e) {
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }


}
