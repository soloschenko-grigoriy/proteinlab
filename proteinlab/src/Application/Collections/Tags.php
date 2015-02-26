<?php

/**
 * Tags collection class
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

class Tags extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'tag';

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getOneBySlug($slug)
  {
    return DB::findOne($this->table, 'slug = ?', array($slug));
  }
  
}