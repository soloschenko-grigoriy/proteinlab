<?php

/**
 * Comment model class
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

class Comment extends RedBean_SimpleModel {

  /**
   * Prepare item before output
   * @param   void
   * @return  \Application\Models\Comment
   */ 
  public function prepare()
  {

    return $this;
  }

}