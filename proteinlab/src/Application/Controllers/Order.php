<?php

/**
 * Order controller class
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

class Order{

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

  


  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function start()
  {    
    $order = $this->_slim->Bookings->prepareOrder();

    $success = false;
    if(isset($_SESSION['success']) && $_SESSION['success'] == true){
      $success = true;
      unset($_SESSION['success']);
    }
    
     $values = array(
      'name' => '',
      'email' => '',
      'phone' => '',
      'address' => '',
      'notes' => '',
    );

    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'order'           => $order['result'],
      'orderCount'      => $this->_slim->Bookings->getOrderCount(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'page'            => 'order',
      'error'           => array(),
      'success'         => $success,
      'total'           => $order['total'],
      'title'           => 'Оформление заказа',
      'values'          => $values,
    );
  }


  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function confirm()
  {
    $name     = filter_input(INPUT_POST, 'name',      FILTER_SANITIZE_STRING);
    $email    = filter_input(INPUT_POST, 'email',     FILTER_VALIDATE_EMAIL);
    $phone    = filter_input(INPUT_POST, 'phone',     FILTER_SANITIZE_STRING);
    $address  = filter_input(INPUT_POST, 'address',   FILTER_SANITIZE_STRING);
    $notes    = filter_input(INPUT_POST, 'notes',     FILTER_SANITIZE_STRING);

    $error = array();
    $values = array(
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'address' => $address,
      'notes' => $notes,
    );

    if(!$name){
      $error['name'] = true;
    }

    // if(!$email){
    //   $error['email'] = true;
    // }

    if(!$phone){
      $error['phone'] = true;
    }

    if(!$address){
      $error['address'] = true;
    }

    if(empty($error)){
      $this->_slim->Bookings->add(array(
        'name'    => $name,
        'email'   => $email,
        'phone'   => $phone,
        'address' => $address,
        'notes'   => $notes,
      ));

      $_SESSION['success'] = true;
      $this->_slim->redirect('/order');
    }
    $order = $this->_slim->Bookings->prepareOrder();
    
    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'order'           => $order['result'],
      'orderCount'      => $this->_slim->Bookings->getOrderCount(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'page'            => 'order',
      'success'         => false,
      'error'           => $error,
      'total'           => $order['total'],
      'values'          => $values,
      'title'           => 'Оформление заказа'
    );
  }

   /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function add()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }

    $productId = filter_input(INPUT_POST, 'product', FILTER_VALIDATE_INT);
    $weightId  = filter_input(INPUT_POST, 'weight',  FILTER_VALIDATE_INT);
    
    if(!$productId || !$weightId){
      $this->_slim->response->setStatus(500);
      return array(
        'result' => false
      );
    }

    if (!isset($_SESSION['order'])) {
      $_SESSION['order'] = array();
    }

    $old = false;
    foreach ($_SESSION['order'] as $key=>$order) {
      if($order['productId'] == $productId && $order['weightId'] == $weightId){
        $old = true;
        $_SESSION['order'][$key]['count'] = $order['count']+1;
        break;
      }
    }

    if($old == false){
      $_SESSION['order'][] = array('productId' => $productId, 'weightId' => $weightId, 'count' => 1);
    }
    
    return array(
      'result' => $_SESSION['order']
    );
  }

  /**
   * Increase value of product order count
   * 
   * @return array
   */
  public function increase()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }

    $productId = filter_input(INPUT_POST, 'product', FILTER_VALIDATE_INT);
    $weightId  = filter_input(INPUT_POST, 'weight',  FILTER_VALIDATE_INT);

    if(!$productId || !$weightId || !isset($_SESSION['order'])){
      $this->_slim->response->setStatus(500);
      return array(
        'result' => false
      );
    }

    foreach ($_SESSION['order'] as $key => $item) {
      if($item['productId'] == $productId && $item['weightId'] == $weightId){
        $_SESSION['order'][$key]['count'] = $item['count']+1;

        break;
      }
    }

    return array(
      'result' => $_SESSION['order']
    );
  }

  /**
   * Decrease value of product order count
   * 
   * @return array
   */
  public function decrease()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }

    $productId = filter_input(INPUT_POST, 'product', FILTER_VALIDATE_INT);
    $weightId  = filter_input(INPUT_POST, 'weight',  FILTER_VALIDATE_INT);

    if(!$productId || !$weightId || !isset($_SESSION['order'])){
      $this->_slim->response->setStatus(500);
      return array(
        'result' => false
      );
    }

    foreach ($_SESSION['order'] as $key => $item) {
      if($item['productId'] == $productId && $item['weightId'] == $weightId){

        if($_SESSION['order'][$key]['count'] > 1){
          $_SESSION['order'][$key]['count'] = $item['count']-1;
        }
        
        break;
      }
    }

    return array(
      'result' => $_SESSION['order']
    );
  }

  /**
   * Remove product from order 
   * 
   * @return array
   */
  public function remove()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }

    $productId = filter_input(INPUT_POST, 'product', FILTER_VALIDATE_INT);
    $weightId  = filter_input(INPUT_POST, 'weight',  FILTER_VALIDATE_INT);

    if(!$productId || !$weightId || !isset($_SESSION['order'])){
      $this->_slim->response->setStatus(500);
      return array(
        'result' => false
      );
    };

    foreach ($_SESSION['order'] as $key => $item) {
      if($item['productId'] == $productId && $item['weightId'] == $weightId){
        unset($_SESSION['order'][$key]);
      }
    }

    return array(
      'result' => $_SESSION['order']
    );
  }

  public function quick()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }
    

    $productId = filter_input(INPUT_POST, 'pid',      FILTER_VALIDATE_INT);
    $weightId  = filter_input(INPUT_POST, 'weight',   FILTER_VALIDATE_INT);
    $price     = filter_input(INPUT_POST, 'price',    FILTER_VALIDATE_INT);
    // $name      = filter_input(INPUT_POST, 'name',     FILTER_SANITIZE_STRING);
    $phone     = filter_input(INPUT_POST, 'phone',    FILTER_SANITIZE_STRING);
    // $address   = filter_input(INPUT_POST, 'address',  FILTER_SANITIZE_STRING);
    // $notes     = filter_input(INPUT_POST, 'notes',    FILTER_SANITIZE_STRING);

    if(!$productId || !$weightId || !$price ||  !$phone){//!$name ||
      $this->_slim->response->setStatus(500);
      return array(
        'result' => false
      );
    }

    $this->_slim->Bookings->quickAdd(array(
        // 'name'      => $name,
        'phone'     => $phone,
        // 'address'   => $address,
        // 'notes'     => $notes,
        'productId' => $productId,
        'weightId'  => $weightId,
        'price'     => $price
      ));
  }

  /**
   * Clear all order
   * 
   * @return array
   */
  public function clear()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }

    unset($_SESSION['order']);

    return array();
  }

}
