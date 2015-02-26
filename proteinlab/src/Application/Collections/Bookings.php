<?php

/**
 * Bookings collection class
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

class Bookings extends CollectionsParent{
  
  /**
   * @var  $table  string
   */
  public $table = 'booking';

  public function add(array $params)
  {
    $booking = DB::dispense($this->table);

    $booking->name    = $params['name'];
    $booking->email   = $params['email'];
    $booking->phone   = $params['phone'];
    $booking->address = $params['address'];
    $booking->notes   = $params['notes'];
    $booking->time    = time();

    $id = DB::store($booking);

    $order = $this->prepareOrder();    
    foreach($order['result'] as $one){
      $this->slim->Bookings2Product->add(array(
        'bookingId' => $id,
        'productId' => $one['product']->id,
        'weightId'  => $one['weight']['id'],
        'quantity'  => $one['count'],
        'price'     => $one['price']
      ));
    };

    unset($_SESSION['order']);
    
  }

  public function quickAdd(array $params)
  {
    $booking = DB::dispense($this->table);

    // $booking->name    = $params['name'];
    $booking->phone   = $params['phone'];
    // $booking->address = $params['address'];
    // $booking->notes   = $params['notes'];
    $booking->time    = time();

    $id = DB::store($booking);

    $this->slim->Bookings2Product->add(array(
      'bookingId' => $id,
      'productId' => $params['productId'],
      'weightId'  => $params['weightId'],
      'quantity'  => 1,
      'price'     => $params['price']
    ));
  }

  public function prepareOrder()
  {
    $result = array();
    $total = 0;
    $totalCount = 0;
    if(isset($_SESSION['order']) && count($_SESSION['order']) > 0){      
      $products = $this->slim->Products->getByIds(array_unique(array_map(function($order){ return $order['productId']; }, $_SESSION['order'])));

      foreach ($_SESSION['order'] as $order){
        $product = reset(array_filter($products, function($one) use($order) { return $one['id'] == $order['productId']; }));
        if(!$product){
          continue;
        }

        $weight  = reset(array_filter($product->weights, function($one) use($order) { return $one['id'] == $order['weightId'];  }));
        if(!$weight){
          continue;
        }

        $total += $weight['price'] * $order['count'];
        $totalCount += $order['count'];
        $result[] = array(
          'product' => $product, 
          'weight'  => $weight,
          'count'   => $order['count'],
          'price'   => $weight['price'] * $order['count']
        );
      }
    }

    return array('result' => $result, 'total' => $total, 'totalCount' => $totalCount);
  }

  public function getOrderCount()
  {
    $order = $this->prepareOrder();

    return $order['totalCount'];
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAll()
  {
    $orders = DB::findAll($this->table, 'status <> "deleted" ORDER BY time DESC');

    if(!$orders){
      return array();
    }
    
    $ids = array_map(function($one){ return $one->id; }, $orders);
    $b2ps = $this->slim->Bookings2Product->findByBookingIds($ids);

    $productIds = array_map(function($one){ return $one->product_id; }, $b2ps);
    $products   = $this->slim->Products->getByIds($productIds);

    $weightIds = array_map(function($one){ return $one->weight_id; }, $b2ps);
    $weights   = $this->slim->Weights->getByIds($weightIds);

    foreach ($orders as $key => $order) {
      $b2p = array_filter($b2ps, function($one) use($order){ return $one->booking_id == $order->id; });

      $bookingproducts = array();
      $total = 0;
      foreach ($b2p as $oneB2p) {
        $product = reset(array_filter($products, function($one) use($oneB2p){ return $one->id == $oneB2p['product_id']; }));
        $weight  = reset(array_filter($weights,  function($one) use($oneB2p){ return $one->id == $oneB2p['weight_id'];  }));

        $total += $oneB2p->price;
        $bookingproducts[] = array(
          'product'  => $product,
          'weight'   => $weight,
          'quantity' => $oneB2p->quantity,
          'price'    => $oneB2p->price
        );
      }

      $orders[$key]['total']    = $total;
      $orders[$key]['products'] = $bookingproducts;
    }

    return $orders;
  }

  public function getNewCount()
  {
    return DB::count($this->table, 'status = "new"');
  }

  public function edit($id, $status)
  {
    $booking = DB::load($this->table, $id);
    $booking->status = $status;

    return DB::store($booking);
  }
}