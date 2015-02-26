<?php

/**
 * Categorys collection class
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

class Categorys extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'category';

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

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAll()
  {
    return DB::findAll($this->table, 'ORDER BY name');
  }
}