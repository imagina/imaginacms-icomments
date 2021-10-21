<?php

return [
  'name' => 'Icomments',
  
  /**
   * By default comments posted are marked as approved. If you want
   * to change this, set this option to true. Then, all comments
   * will need to be approved by setting the `approved` column to
   * `true` for each comment.
   *
   * To see only approved comments use this code in your view:
   *
   * @comments([
   *         'model' => $book,
   *         'approved' => true
   *     ])
   *
   */
  'approval_required' => false,
  
  /**
   * Set this option to `true` to enable guest commenting.
   *
   * Visitors will be asked to provide their name and email
   * address in order to post a comment.
   */
  'guest_commenting' => false,
  
  "transformersCommentable" => [
    
    "Modules\\Ievent\\Entities\\Event" => "Modules\\Ievent\\Transformers\\EventSimpleTransformer",
    "Modules\\Iteam\\Entities\\Team" => "Modules\\Iteam\\Transformers\\SimpleTeamTransformer"
  
  ],
  
  "availableEntities" => [
    "Modules\\Ievent\\Entities\\Event",
    "Modules\\Iteam\\Entities\\Team"
  ]

];
