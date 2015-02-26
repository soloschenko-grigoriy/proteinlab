<?php
/**
 * Comments collection class
 *  
 * @package     Application
 * @subpackage  Collections
 * 
 * @author      Soloschenko G. soloschenko@gmail.com
 * @copyright   Soloschenko G. soloschenko@gmail.com
 * 
 * @version     1.0
 */ 
namespace Application\Collections;

use \R as DB;

class Comments extends CollectionsParent{
  
  /**
   * @var  $table  string
   */
  public $table = 'comment';

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByPostIds(array $ids)
  {
    return DB::findAll($this->table, 'type = "post" AND approved="yes" AND pid IN ('.implode(',', $ids).')');
  }

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function getPostComments()
  {
    return $this->prepare(DB::findAll($this->table, 'type = "post"'), 'post');
  }

   /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function getItemComments()
  {
    return $this->prepare(DB::findAll($this->table, 'type = "item"'), 'item');
  }

  public function prepare($coments, $type)
  {
    if(empty($coments)){
      return $coments;
    }

    if($type == 'post'){
      $posts = $this->slim->Posts->findByIds(array_map(function($comment) { return $comment->pid; }, $coments));
    }else{
      $posts = $this->slim->Products->getByIds(array_map(function($comment) { return $comment->pid; }, $coments));
    }

    foreach ($coments as $key => $coment) {
      $item = reset(array_filter($posts, function($post) use($coment){ return $coment->pid == $post['id'];}));

      if($item){
        $coments[$key]['item'] = $item;
      }else{
        unset($coments[$key]);
      }
      
    }

    return $coments;
  }

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByProductIds(array $ids)
  {
    return DB::findAll($this->table, 'type = "item" AND approved="yes" AND pid IN ('.implode(',', $ids).')');
  }

  public function add(array $data)
  { 
    $comment = DB::dispense($this->table);

    $comment->pid   = $data['pid'];
    $comment->name  = $data['name'];
    $comment->body  = $data['body'];
    $comment->type  = $data['type'];
    $comment->email = $data['email'];
    
    $comment->time  = time();

    return DB::store($comment);
  }

  public function edit($id, $approved)
  {
    $comment = DB::load($this->table, $id);
    $comment->approved = $approved;

    if($approved == 'delete'){
      DB::trash($comment); 
    }else{
      DB::store($comment);
    }

    return true;
  } 
}