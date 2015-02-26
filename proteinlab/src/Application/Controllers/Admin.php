<?php

/**
 * Admin controller class
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

class Admin{

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
    if(!$this->_slim->Users->isAuth()){
      $this->_slim->redirect('/admin/login');
    }

    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'index'
    );
  }

  /**
   * Login route handler
   * 
   * @return array
   */
  public function login()
  {
    if($this->_slim->Users->isAuth()){
      $this->_slim->redirect('/admin');
    }

    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password',  FILTER_SANITIZE_STRING);

    if($email && $password && $this->_slim->Users->login($email, $password)){
      $this->_slim->redirect('/admin');
    }

    return array();
  }

  /**
   * Logout route handler
   * 
   * @return array
   */
  public function logout()
  {
    if(!$this->_slim->Users->isAuth()){
      $this->_slim->redirect('/admin/login');
    }

    if($this->_slim->Users->logout()){
      $this->_slim->redirect('/admin/login');
    }

    return array();
  }

  /**
   * Main route handler
   * 
   * @return array
   */
  public function main()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'main'
    );
  }

  /**
   * About route handler
   * 
   * @return array
   */
  public function about()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'about'
    );
  }

  /**
   * Contacts route handler
   * 
   * @return array
   */
  public function contacts()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'contacts'
    );
  }

  /**
   * Products route handler
   * 
   * @return array
   */
  public function products()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(), 
      'products' => $this->_slim->Products->getAllInNativeOrder(true),
      'page'     => 'products'
    );
  }

  /**
   * Products route handler
   * 
   * @return array
   */
  public function one($id = null)
  {
    $product = null;
    $related = array();
    if($id){
      $product = $this->_slim->Products->getOneById($id);
      $related = array_map(function($related){ return $related['related_id'];}, $product->related);
    }
    
    // print_r($product->related); die();
    return array(
      'manufacturers' => $this->_slim->Manufacturers->getAll(),
      'categories'    => $this->_slim->Categorys->getAll(),
      'newCount'      => $this->_slim->Bookings->getNewCount(),
      'products'      => $this->_slim->Products->getAll(),
      'related'       => $related,
      'product'       => $product,
      'page'          => 'one'
    );
  }

  public function saveOne($id = null)
  {
    $values = filter_input_array(INPUT_POST, array(
      'name'             => FILTER_SANITIZE_STRING,
      'desc'             => FILTER_SANITIZE_SPECIAL_CHARS,
      'info'             => FILTER_SANITIZE_SPECIAL_CHARS,
      'photos'           => array('flags' => FILTER_REQUIRE_ARRAY),
      'weights'          => array('flags' => FILTER_REQUIRE_ARRAY),
      'related'          => array('flags' => FILTER_REQUIRE_ARRAY),
      'keywords'         => FILTER_SANITIZE_STRING,
      'category'         => FILTER_VALIDATE_INT,
      'manufacturer'     => FILTER_VALIDATE_INT,
      'meta_description' => FILTER_SANITIZE_STRING,
    ));

    if(!$values || empty($values['weights'])){
      return $this->one($id);
    }

    $product = ($id) ? $this->_slim->Products->edit($id, $values) : $this->_slim->Products->add($values);
    

    $this->_slim->redirect('/admin/one/'.$product->id);
  }

  public function deleteOne($id)
  {
    if(!$id){
      return;
    }

    return $this->_slim->Products->delete($id);
  }

  public function upload()
  {
    return $this->_slim->Photos->upload();
  }

  /**
   * Categories route handler
   * 
   * @return array
   */
  public function categories()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'categories'
    );
  }

  /**
   * Manufactures route handler
   * 
   * @return array
   */
  public function manufactures()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'manufactures'
    );
  }

  /**
   * Top products route handler
   * 
   * @return array
   */
  public function top()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'top'
    );
  }

  /**
   * Articles route handler
   * 
   * @return array
   */
  public function articles()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'posts'    => $this->_slim->Posts->getAll(),
      'page' => 'articles'
    );
  }


  /**
   * Products route handler
   * 
   * @return array
   */
  public function articlesOne($id = null)
  {
    $article = null;
    if($id){
      $article = $this->_slim->Posts->getOneById($id);
    }
    
    return array(
      'articleTags' => $article ? array_map(function($tag){ return $tag->id; }, $article->tags) : array(),
      'newCount'    => $this->_slim->Bookings->getNewCount(),
      'article'     => $article,
      'page'        => 'articlesOne',
      'tags'        => $this->_slim->Tags->getAll(),
    );
  }

  public function saveArticlesOne($id = null)
  {
    $values = filter_input_array(INPUT_POST, array(
      'tags'        => array('flags' => FILTER_REQUIRE_ARRAY),
      'title'       => FILTER_SANITIZE_STRING,
      'body'        => FILTER_SANITIZE_SPECIAL_CHARS,
      'short'       => FILTER_SANITIZE_SPECIAL_CHARS,
      'shortest'    => FILTER_SANITIZE_SPECIAL_CHARS,
      'keywords'    => FILTER_SANITIZE_STRING,
      'description' => FILTER_SANITIZE_STRING
    ));

    if(!$values){
      return $this->one($id);
    }

    $article = ($id) ? $this->_slim->Posts->edit($id, $values) : $this->_slim->Posts->add($values);
    

    $this->_slim->redirect('/admin/articles/one/'.$article->id);
  }

  public function deleteArticlesOne($id)
  {
    if(!$id){
      return;
    }

    return $this->_slim->Posts->delete($id);
  }

  /**
   * Articles route handler
   * 
   * @return array
   */
  public function postComments()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'comments' => $this->_slim->Comments->getPostComments(),
      'page'     => 'comments',
      'type'     => 'post'
    );
  }

  /**
   * Articles route handler
   * 
   * @return array
   */
  public function itemComments()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'comments' => $this->_slim->Comments->getItemComments(),
      'page'     => 'comments',
      'type'     => 'item'
    );
  }

  public function saveComment($id)
  {
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    if(!$id || !$status){
      return;
    }
  
    return $this->_slim->Comments->edit($id, $status);
  }

  /**
   * Tags route handler
   * 
   * @return array
   */
  public function tags()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'page' => 'tags'
    );
  }

  /**
   * Orders route handler
   * 
   * @return array
   */
  public function orders()
  {

    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'orders'   => $this->_slim->Bookings->getAll(),
      'page'     => 'orders'
    );
  }

  /**
   * Orders route handler
   * 
   * @return array
   */
  public function changeOrder($id)
  {
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    if(!$id || !$status){
      return;
    }
  
    return $this->_slim->Bookings->edit($id, $status);

  }
  /**
   * Feedback route handler
   * 
   * @return array
   */
  public function feedback()
  {
    return array(
      'newCount' => $this->_slim->Bookings->getNewCount(),
      'feedbacks' => $this->_slim->Feedbacks->getAll(),
      'page' => 'feedback'
    );
  }

  /**
   * Prarser route
   * @return [type] [description]
   */
  public function parser()
  {
    // $this->_slim->Products->getAllInNativeOrder(true);
    // echo 1;
  }
}
