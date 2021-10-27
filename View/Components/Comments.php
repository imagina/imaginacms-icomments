<?php

namespace Modules\Icomments\View\Components;

use Illuminate\View\Component;

class Comments extends Component
{


  public $view;
  public $items;
  public $params;
  public $model;
  public $approved;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($layout = "comments-layout-1", $model, $approved = false,$params = [])
  {

    $this->view = "icomments::frontend.components.comments.layouts.$layout.index";
    $this->model = $model;
    $this->approved = $approved;
    $this->params = $params;

    $this->getItems();
    
  }

  private function getItems(){

    /*
    * Option 1 - Require add trait with relations in the model (Example Trait WithComments - Icommerce Module)
    */
    if($this->approved)
      $this->items = $this->model->approvedComments;
    else
      $this->items = $this->model->comments;
   

    /*
    * Option 2 - Just testing
    */
    /*
    $this->params['filter']['commentableType'] = get_class($this->model);
    $this->params['filter']['commentableId'] = $this->model->id ;
    
    $repository = app("Modules\Icomments\Repositories\CommentRepository");
    $this->items = $repository->getItemsBy(json_decode(json_encode($this->params)));
    */

    // In View
    //<x-icomments::comments :model="$product" :params="['filter' => ['approved' => true]]"/>

  }
  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|string
   */
  public function render()
  {
    return view($this->view);
  }

}