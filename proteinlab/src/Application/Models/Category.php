<?php

/**
 * Category model class
 *  
 * @package     Application
 * @subpackage  Models
 * 
 * @author      Soloschenko G. soloschenko@gmail.com
 * @copyright   Soloschenko G. soloschenko@gmail.com
 * 
 * @version     1.0
 */ 
namespace Application\Models;

use RedBean_SimpleModel;

class Category extends RedBean_SimpleModel {

  /**
   * @var  $_table  string
   */
  private $_table = 'category';

  /**
   * Prepare item before output
   * 
   * @return  \Application\Models\Category
   */ 
  public function prepare()
  {
    return $this;
  }



}