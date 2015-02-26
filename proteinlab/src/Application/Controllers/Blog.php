<?php

/**
 * Blog controller class
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

class Blog{

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
  public function start($current = 1)
  {
    $order = $this->_slim->Bookings->prepareOrder();

    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'pagination'      => $this->_slim->Posts->getPaginationCount(),
      'orderCount'      => $order['totalCount'],
      'current'         => $current,
      'widget'          => $this->_slim->Widgets->getForBlog(),
      'order'           => $order['result'],
      'total'           => $order['total'],
      'title'           => 'Блог о спорте',
      'posts'           => $this->_slim->Posts->getByPage($current),
      'page'            => 'blog',
      'tags'            => $this->_slim->Tags->getAll(),
      'url'             => '/blog'
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function tags($slug, $current = 1)
  {
    $order = $this->_slim->Bookings->prepareOrder();
    $posts = $this->_slim->Posts->getByTag($slug, $current);
    $pagination = $this->_slim->Posts->getTagsPaginationCount($slug);
    if(empty($posts)){
      $pagination = 0;
    }
    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'pagination'      => $pagination,
      'currentTag'      => $slug,
      'orderCount'      => $order['totalCount'],
      'current'         => $current,
      'widget'          => $this->_slim->Widgets->getForBlog(),
      'order'           => $order['result'],
      'total'           => $order['total'],
      'title'           => 'Блог о спорте',
      'posts'           => $posts,
      'page'            => 'blog',
      'tags'            => $this->_slim->Tags->getAll(),

      'url'             => '/blog/tags/'.$slug
    );
  }

  /**
   * Find all neccessary stuff and return to router
   * 
   * @return array
   */
  public function item($slug)
  {
    $order = $this->_slim->Bookings->prepareOrder();
    $post  = $this->_slim->Posts->getOneBySlug($slug);

    return array(
      'manufacturers'   => $this->_slim->Manufacturers->getAll(),
      'categories'      => $this->_slim->Categorys->getAll(),
      'orderCount'      => $order['totalCount'],
      'widget'          => $this->_slim->Widgets->getForBlog(),
      'order'           => $order['result'],
      'title'           => $post->title,
      'description'     => $post->description,
      'keywords'        => $post->keywords,
      'total'           => $order['total'],
      'page'            => 'blog-item',
      'post'            => $post,
      'tags'            => $this->_slim->Tags->getAll(),
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
      'type'  => 'post'
    ));

    return array('result' => true);
  }
}
