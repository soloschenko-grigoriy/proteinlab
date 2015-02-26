<?php

/**
 * Manufacturers collection class
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

class Manufacturers extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'manufacturer';

  /**
   * Fins and return category by slug
   * 
   * @param  int $categoryId
   * @return Application\Models\Category 
   */
  public function getOneBySlug($slug)
  {
    if(!$slug){
      return null;
    }

    return DB::findOne($this->table, 'slug = :slug', array('slug' => $slug));
  }
  
}