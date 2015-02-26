<?php

/**
 * Feedbacks collection class
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

class Feedbacks extends CollectionsParent{
  
  /**
   * @var  $table  string
   */
  public $table = 'feedback';

  public function add(array $data)
  { 
    $comment = DB::dispense($this->table);

    $comment->name    = $data['name'];
    $comment->body    = $data['body'];
    $comment->email   = $data['email'];
    $comment->subject = $data['subject'];
    $comment->time    = time();

    return DB::store($comment);
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAll()
  {
    return DB::findAll($this->table, 'ORDER BY time DESC');
  }
}