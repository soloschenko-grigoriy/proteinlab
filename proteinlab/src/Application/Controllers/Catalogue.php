<?php

/**
 * Catalogue controller class
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

class Catalogue{

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

  public function general($type)
  {
    $categories    = $this->_slim->Categorys->getAll();
    $manufacturers = $this->_slim->Manufacturers->getAll();
    $order = $this->_slim->Bookings->prepareOrder();

    return array(
      'manufacturers' => $manufacturers,
      'categories'    => $categories,
      'order'         => $order['result'],
      'total'         => $order['total'],
      'orderCount'    => $order['totalCount'],
      'type'          => $type,
      'page'          => 'catalogue-category',
      'title'         => ($type == 'category') ? 'Категории спортивного питания' : 'Производители спортивного питания',
      'keywords'      => 'добавки, белки, жиросжигатели, витамины, креатин, NO2, бодибилдинг, спортивные добавки, фитнес, низкие цены, питание бодибилдинг, бодибилдинг киев, спортивное питание',
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function start($type, $category = null)
  {
    if($type == 'category'){
      $currentCategory      = $this->_slim->Categorys->getOneBySlug($category);
      $currentManufacturer  = null;
      $products             = $currentCategory ? $this->_slim->Products->getByCategory($currentCategory->id) : array();
      $current              = $currentCategory;
    }else{
      $currentCategory      = null;
      $currentManufacturer  = $this->_slim->Manufacturers->getOneBySlug($category);
      $products             = $this->_slim->Products->getByManufacturer($currentManufacturer->id);
      $current              = $currentManufacturer;
    }
    
    $order = $this->_slim->Bookings->prepareOrder();

    if(!$current){
      $this->_slim->pass();
    }

    return array(
      'currentManufacturer' => $currentManufacturer,
      'currentCategory'     => $currentCategory,
      'manufacturers'       => $this->_slim->Manufacturers->getAll(),
      'categories'          => $this->_slim->Categorys->getAll(),
      'order'               => $order['result'],
      'total'               => $order['total'],
      'orderCount'          => $order['totalCount'],
      'current'             => $current,
      'products'            => $products,
      'page'                => 'catalogue',
      'type'                => $type,
      'title'               => $current ? $current->name : null,
      'keywords'            => $current ? $current->keywords : null,
      'description'         => $current ? $current->meta_desc : null
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function item($slug)
  {
    $product          = $this->_slim->Products->getOneBySlug($slug);

    if(!$product){
      $this->_slim->pass();
    }
    
    $currentCategory  = $this->_slim->Categorys->getOneById($product->category_id);
    $order = $this->_slim->Bookings->prepareOrder();
    
    return array(
      'manufacturers'     => $this->_slim->Manufacturers->getAll(),
      'currentCategory'   => $currentCategory,
      'comments'          => $this->_slim->Comments->findByProductIds(array($product->id)),
      'order'             => $order['result'],
      'total'             => $order['total'],
      'orderCount'        => $order['totalCount'],
      'categories'        => $this->_slim->Categorys->getAll(),
      'product'           => $product,
      'top8'              => $this->_slim->Top8s->getAll(),
      'page'              => 'catalogue-item',
      'title'             => $product->manufacturer->name. ' '.$product->name,
      'description'       => $product->meta_description,
      'keywords'          => $product->keywords
    );
  }

  public function comment()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }

    $pid   = filter_input(INPUT_POST, 'pid',   FILTER_VALIDATE_INT);
    $name  = filter_input(INPUT_POST, 'name',  FILTER_SANITIZE_STRING);
    $body  = filter_input(INPUT_POST, 'body',  FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
  
    if(!$pid || !$name || !$body || !$email){
      $this->_slim->response->setStatus(500);
      
      return array('result' => false);
    }

    $this->_slim->Comments->add(array(
      'pid'   => $pid,
      'name'  => $name,
      'body'  => $body,
      'email' => $email,
      'type'  => 'item'
    ));

    return array('result' => true);
  }
}
