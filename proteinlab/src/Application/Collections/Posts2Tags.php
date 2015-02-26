<?php

/**
 * Posts2Tags collection class
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

class Posts2Tags extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'post2tags';

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByPostIds(array $ids)
  {
    return DB::findAll($this->table, 'post_id IN ('.implode(',', $ids).')');
  }

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByTagId($id)
  {
    return DB::findAll($this->table, 'tag_id = ?', array($id));
  }

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByTagIds(array $ids)
  {
    return DB::findAll($this->table, 'tag_id IN ('.implode(',', $ids).')');
  }
}