<?php

/**
 * Widget model class
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

class Widget extends RedBean_SimpleModel {

  /**
   * Prepare item before output
   * 
   * @return  \Application\Models\Widget
   */ 
  public function prepare()
  {

    return $this;
  }

}