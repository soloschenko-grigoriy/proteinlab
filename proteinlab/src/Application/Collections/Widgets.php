<?php

/**
 * Widgets collection class
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

class Widgets extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'widget';

  public function getForBlog()
  {
    return array(
      'top'     => DB::findOne($this->table, 'type = "blogTop"'),
      'middle'  => DB::findOne($this->table, 'type = "blogMiddle"'),
      'bottom'  => DB::findOne($this->table, 'type = "blogBottom"')
    );
  }
}