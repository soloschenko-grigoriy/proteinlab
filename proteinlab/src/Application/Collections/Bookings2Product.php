<?php

/**
 * Bookings2Product collection class
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

class Bookings2Product extends CollectionsParent{
  
  /**
   * @var  $table  string
   */
  public $table = 'booking2product';

  public function add(array $params)
  {
    $item = DB::dispense('booking2product');

    $item->booking_id = $params['bookingId'];
    $item->product_id = $params['productId'];
    $item->weight_id  = $params['weightId'];
    $item->quantity   = $params['quantity'];
    $item->price      = $params['price'];

    return DB::store($item);
  }

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByBookingIds(array $ids)
  {
    return DB::findAll($this->table, 'booking_id IN ('.implode(',', $ids).')');
  }
}