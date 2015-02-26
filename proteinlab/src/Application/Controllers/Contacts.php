<?php

/**
 * Contacts controller class
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

class Contacts{

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
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'order'           => $order['result'],
      'total'           => $order['total'],
      'orderCount'      => $order['totalCount'],
      'page'            => 'contacts',
      'title'           => 'Контактая информация'
    );
  }

  public function send()
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
