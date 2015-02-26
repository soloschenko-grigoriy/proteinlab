<?php

/**
 * General collection class
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

class CollectionsParent{
  
  /**
   * @var  $slim  \Slim\Slim
   */
  public $slim;
  
  /**
   * Constructor
   * @param   \Slim\Slim $slim
   * @return  void
   */ 
  public function __construct(\Slim\Slim $slim)
  {
    $this->slim = $slim;
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getOneById($id)
  {
    return DB::load($this->table, $id);
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAll()
  {
    return DB::findAll($this->table);
  }

  /**
   * Fins and return category by slug
   * 
   * @param  int $categoryId
   * @return Application\Models\Category 
   */
  public function getByIds(array $ids)
  {
    return DB::findAll($this->table, 'id IN ('.implode(',', $ids).')');
  }
}