<?php

/**
 * Weights collection class
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

use R as DB;

class Weights extends CollectionsParent{
  
  /**
   * @var  $table  string
   */
  public $table = 'weight';

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByProductIds(array $ids)
  {
    return DB::findAll($this->table, 'product_id IN ('.implode(',', $ids).')');
  }
}