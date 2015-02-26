<?php

/**
 * Weight model class
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

class Weight extends RedBean_SimpleModel {

  /**
   * Prepare item before output
   * 
   * @return  \Application\Models\Weight
   */ 
  public function prepare()
  {

    return $this;
  }

}