<?php

namespace Modules\Icomments\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model
use Modules\Icomments\Entities\Comment;
use Modules\Icomments\Repositories\CommentRepository;

use Illuminate\Http\Request;
//Default transformer
use Modules\Core\Icrud\Transformers\CrudResource;

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
            $modelData = $request->input('attributes') ?? [];//Get data  
            
            //Validate Request
            if (isset($this->model->requestValidation['create'])) {
              $this->validateRequestApi(new $this->model->requestValidation['create']($modelData));
            }

            // Check if the comment required approval and set the value to approved
            $data['approved']=!config('comments.approval_required');

            // Save model
            $model = $this->modelRepository->create($modelData);

            //Response
            $response = ["data" => CrudResource::transformData($model)];
            \DB::commit(); //Commit to Data Base
            
        } catch (\Exception $e) {
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }



}
