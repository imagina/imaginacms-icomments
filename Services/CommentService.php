<?php

namespace Modules\Icomments\Services;

use Modules\Icomments\Repositories\CommentRepository;

class CommentService
{

	private $comment;


	public function __construct(
		CommentRepository $comment
	){
		$this->comment = $comment;
	}

	/**
    * Create a Comment
    * @param $model
    * @param $comment
    * @return 
    */
	public function create($model,$comment){

		
     	try {

     		$data = [
     			"user_id" => $model->user_id,
     			"commentable_type" => get_class($model),
     			"commentable_id" => $model->id,
     			"approved" => !config('comments.approval_required') // Check if the comment required approval and set the value to approved
     		];
     		$dataToSave = array_merge($data,$comment);
     		
     		$comment = $this->comment->create($dataToSave);

			return $comment;

     	} catch (\Exception $e) {

     		//dd($e);
     		//\Log::info('Icomment: Comment Service - Create - ERROR: '.$e->getMessage().' Code:'.$e->getErrorCode());
        	\Log::error("error: " . $e->getMessage() . "\n" . $e->getFile() . "\n" . $e->getLine() . $e->getTraceAsString());


      	}

	}


}