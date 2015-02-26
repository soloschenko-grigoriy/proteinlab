<?php

/**
 * Index controller class
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

class Index{

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

    return array(
      'manufacturers' => $this->_slim->Manufacturers->getAll(),
      'order'         => $order['result'],
      'total'         => $order['total'],
      'orderCount'    => $order['totalCount'],
      'categories'    => $this->_slim->Categorys->getAll(),
      'posts'         => $this->_slim->Posts->getForHomePage(),
      'top8'          => $this->_slim->Top8s->getAll(),
      'page'          => 'index',
      'title'         => ''
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function page404()
  {
    $order = $this->_slim->Bookings->prepareOrder();

    return array(
      'manufacturers' => $this->_slim->Manufacturers->getAll(),
      'order'         => $order['result'],
      'total'         => $order['total'],
      'orderCount'    => $order['totalCount'],
      'categories'    => $this->_slim->Categorys->getAll(),
      'posts'         => $this->_slim->Posts->getForHomePage(),
      'top8'          => $this->_slim->Top8s->getAll(),
      'page'          => '../errors/404',
      'title'         => 'Ошибка 404'
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function page500()
  {
    $order = $this->_slim->Bookings->prepareOrder();

    return array(
      'manufacturers' => $this->_slim->Manufacturers->getAll(),
      'order'         => $order['result'],
      'total'         => $order['total'],
      'orderCount'    => $order['totalCount'],
      'categories'    => $this->_slim->Categorys->getAll(),
      'posts'         => $this->_slim->Posts->getForHomePage(),
      'top8'          => $this->_slim->Top8s->getAll(),
      'page'          => '../errors/500',
      'title'         => 'Ошибка 500'
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function about()
  {
    $order = $this->_slim->Bookings->prepareOrder();
    
    return array(
      'manufacturers' => $this->_slim->Manufacturers->getAll(),
      'categories'    => $this->_slim->Categorys->getAll(),
      'order'         => $order['result'],
      'total'         => $order['total'],
      'orderCount'    => $order['totalCount'],
      'page'          => 'about',
      'title'         => 'Информация о нас'
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function contacts()
  { 
    $order = $this->_slim->Bookings->prepareOrder();
    
    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'order'           => $order['result'],
      'total'           => $order['total'],
      'orderCount'      => $order['totalCount'],
      'page'            => 'contacts',
      'title'           => 'Контактая информация'
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function delivery()
  { 
    $order = $this->_slim->Bookings->prepareOrder();
    
    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'order'           => $order['result'],
      'total'           => $order['total'],
      'orderCount'      => $order['totalCount'],
      'page'            => 'delivery',
      'title'           => 'Оплата и доставка'
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function rules()
  { 
    $order = $this->_slim->Bookings->prepareOrder();
    
    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'order'           => $order['result'],
      'total'           => $order['total'],
      'orderCount'      => $order['totalCount'],
      'page'            => 'rules',
      'title'           => 'Правила'
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function testimonials()
  { 
    $order = $this->_slim->Bookings->prepareOrder();
    
    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'order'           => $order['result'],
      'total'           => $order['total'],
      'orderCount'      => $order['totalCount'],
      'page'            => 'testimonials',
      'title'           => 'Рекомендации'
    );
  }
  public function contactsSend()
  {
    if(!$this->_slim->request->isAjax()){
      $this->_slim->response->redirect('/foo', 405);
    }

    $name     = filter_input(INPUT_POST, 'name',    FILTER_SANITIZE_STRING);
    $body     = filter_input(INPUT_POST, 'body',    FILTER_SANITIZE_STRING);
    $email    = filter_input(INPUT_POST, 'email',   FILTER_VALIDATE_EMAIL);
    $subject  = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
  
    if(!$subject || !$name || !$body || !$email){
      $this->_slim->response->setStatus(500);
      
      return array('result' => false);
    }

    $this->_slim->Feedbacks->add(array(
      'name'    => $name,
      'body'    => $body,
      'email'   => $email,
      'subject' => $subject,
    ));

    return array('result' => true);
  }

}
