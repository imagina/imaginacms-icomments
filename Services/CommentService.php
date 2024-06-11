<?php

namespace Modules\Icomments\Services;

use Modules\Icomments\Repositories\CommentRepository;

class CommentService
{

	private $comment;
	private $log = "Icomments:: Services|CommentService";

    public function __construct(
        CommentRepository $comment
    ) {
        $this->comment = $comment;
    }

	/**
    * Create a Comment
    * @param $model
    * @param $comment
    * @return
    */
	public function create($model,$data){


     	try {

     		$commentData = [
     			"user_id" => $data["user_id"] ?? $model->user_id ?? \Auth::id(),
     			"commentable_type" => $data["commentable_type"] ?? get_class($model),
     			"commentable_id" => $data["commentable_id"] ?? $model->id,
     			"comment" => $data["comment"],
     			"approved" => $data["approved"] ?? !config('comments.approval_required'), // Check if the comment required approval and set the value to approved
     			"internal" => $data["internal"] ?? false,
				"type" => $data["type"] ?? null
     		];

            //Validation Gallery
            if (isset($data['medias_multi'])) {
                $commentData['medias_multi'] = $data['medias_multi'];
            }

            $comment = $this->comment->create($commentData);

			return $comment;

     	} catch (\Exception $e) {
     		//dd("ERRORR",$e);
     		\Log::error('Icomments: Services|CommentService|Message: '.$e->getMessage().' | FILE: '.$e->getFile().' | LINE: '.$e->getLine());

      	}

	}

	/**
	 * Update Types in Comments searching a text
	 */
	public function updateTypeByText(array $dataToSearch, string $commentableType = null)
	{
		\Log::info($this->log.'updateTypeByText');

		foreach ($dataToSearch as $data) {

			//\Log::info($this->log.'updateTypeByText|type:'.$data['type'].'||search:'.$data['text']);

			//Remember that "deleted comments" are not included
			$params = [
				"filter" => [
					"search" => $data['text'],
					"type" => null //ONLY NULL (Old comments)
				]
			];

			//Only with this entity
			if(!is_null($commentableType))
				$params["filter"]["commentable_type"] = $commentableType;

			$comments = $this->comment->getItemsBy(json_decode(json_encode($params)));

			\Log::info($this->log.'updateTypeByText|CommentsToUpdate: '.count($comments)." to add Type: ".$data['type']);
			if(count($comments)>0){
				//Update Type
				foreach ($comments as $key => $comment) {
					$comment->type = $data['type'];
					$comment->save();
				}
			}

		}

	}


}