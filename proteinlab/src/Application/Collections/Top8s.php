<?php

/**
 * Top8s collection class
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

class Top8s extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'top8';

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAll()
  {
    $top = DB::findAll($this->table, 'ORDER BY pos DESC');

    if($top && count($top) > 0){
      $ids = array_map(function($item){ return $item['product_id']; }, $top);
      return $this->slim->Products->getByIds(array_unique($ids));
    }else{
      return array();
    }
  }

}