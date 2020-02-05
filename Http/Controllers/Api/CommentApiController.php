<?php

namespace Modules\Icomments\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Icomments\Http\Requests\CreateCommentRequest;
use Modules\Icomments\Repositories\CommentRepository;
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;


class CommentApiController extends BaseApiController
{
    /**
     * @var CategoryStoreRepository
     */
    private $comment;

    public function __construct(CommentRepository $comment)
    {
        parent::__construct();

        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            //Get Parameters from URL.
            $params = $this->getParamsRequest($request);

            //Request to Repository
            $dataEntity = $this->comment->getItemsBy($params);

            //Response
            $response = ["data" => CommentTransformer::collection($dataEntity)];

            //If request pagination add meta-page
            $params->page ? $response["meta"] = ["page" => $this->pageTransformer($dataEntity)] : false;
        } catch (\Exception $e) {
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $criteria
     * @param Request $request
     * @return Response
     */
    public function show($criteria, Request $request)
    {
        try {
            //Get Parameters from URL.
            $params = $this->getParamsRequest($request);

            //Request to Repository
            $dataEntity = $this->comment->getItem($criteria, $params);

            //Break if no found item
            if (!$dataEntity) throw new Exception('Item not found', 204);

            //Response
            $response = ["data" => new CommentTransformer($dataEntity)];

            //If request pagination add meta-page
            $params->page ? $response["meta"] = ["page" => $this->pageTransformer($dataEntity)] : false;
        } catch (\Exception $e) {
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
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


            $user=null;

            // If guest commenting is turned off, authorize this action.
            if (config('asgard.comments.guest_commenting') == false) {
                $user = \Auth::guard('api')->user() ?? \Auth::user();
            }

            // Define guest rules if user is not logged in.
            if (!$user) {
                $guest_rules = [
                    'guest_name' => 'required|string|max:255',
                    'guest_email' => 'required|string|email|max:255',
                ];
            }
            $this->validateRequestApi(new CreateCommentRequest($data));

            $model = $data['commentable_type']::find($request->commentable_id);

            $data['approved']=!config('comments.approval_required');

            //Break if no found item
            if (!$model) throw new Exception('Item not found', 404);

            $dataEntity = $this->comment->create($data);

            //Response
            $response = ["data" => new CommentTransformer($dataEntity)];
            \DB::commit(); //Commit to Data Base
        } catch (\Exception $e) {
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }
        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $criteria
     * @param Request $request
     * @return Response
     */
    public function update($criteria, Request $request)
    {
        \DB::beginTransaction(); //DB Transaction
        try {
            //Get data
            $data = $request->input('attributes') ?? [];//Get data

            //Validate Request
            $this->validateRequestApi(new UpdateCommentRequest($data));

            //Get Parameters from URL.
            $params = $this->getParamsRequest($request);

            //Request to Repository
            $dataEntity = $this->comment->getItem($criteria, $params);

            if (!$dataEntity) throw new Exception('Item not found', 204);

            //Request to Repository
            $this->comment->update($dataEntity, $data);

            //Response
            $response = ["data" => 'Item Updated'];
            \DB::commit();//Commit to DataBase
        } catch (\Exception $e) {
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $criteria
     * @param Request $request
     * @return Response
     */

    public function delete($criteria, Request $request)
    {
        \DB::beginTransaction();
        try {
            //Get params
            $params = $this->getParamsRequest($request);

            //Request to Repository
            $dataEntity = $this->comment->getItem($criteria, $params);
            if (!$dataEntity) throw new Exception('Item not found', 204);
            //call Method delete
            $this->comment->delete($dataEntity);


            //Response
            $response = ["data" => "Item deleted"];
            \DB::commit();//Commit to Data Base
        } catch (\Exception $e) {
            \DB::rollback();//Rollback to Data Base
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }

    public function updateAll(Request $request)
    {
        try {
            //Get Parameters from URL.
            $params = $this->getParamsRequest($request);
            $data = $request->input('attributes') ?? [];//Get data
            //Request to Repository
            $dataEntity = $this->comment->getItemsBy($params);
            $crterians = $dataEntity->pluck('id');
            $dataEntity = $this->comment->updateItems($crterians, $data);
            //Response
            $response = ["data" => NotificationTransformer::collection($dataEntity)];
            //If request pagination add meta-page
            $params->page ? $response["meta"] = ["page" => $this->pageTransformer($dataEntity)] : false;
        } catch (\Exception $e) {
            \Log::error($e);
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }

    public function deleteAll(Request $request)
    {
        try {
            //Get Parameters from URL.
            $params = $this->getParamsRequest($request);
            //Request to Repository
            $dataEntity = $this->comment->getItemsBy($params);
            $crterians = $dataEntity->pluck('id');
            $this->comment->deleteItems($crterians);
            //Response
            $response = ["data" => "Items deleted"];

        } catch (\Exception $e) {
            \Log::error($e);
            $status = $this->getStatusError($e->getCode());
            $response = ["errors" => $e->getMessage()];
        }

        //Return response
        return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
    }

}
