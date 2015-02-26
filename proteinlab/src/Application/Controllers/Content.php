<?php

/**
 * About controller class
 *  
 * @package     Application
 * @subpackage  Controllers
 * 
 * @author      Soloschenko G. soloschenko@gmail.com
 * @copyright   Soloschenko G. soloschenko@gmail.com
 * 
 * @version     1.0
 */ 
namespace Application\Controllers;

class Content{

  /*
   * @property $_slim \Slim\Slim
   */
  private $_slim;

  /**
   * Constructor
   * @param \Slim\Slim $slim
   * @return void
   */ 
  public function __construct(\Slim\Slim $slim)
  {
    $this->_slim = $slim;
  }

  

}
