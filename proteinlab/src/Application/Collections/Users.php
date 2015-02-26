<?php

/**
 * Users collection class
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

class Users extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'user';

  /**
   * Check if some user is auth
   * 
   * @return boolean
   */
  public function isAuth()
  {
    if(!isset($_COOKIE['user']) || empty($_COOKIE['user'])){
      return false;
    }

    if(DB::findOne('user','keycode = ?', array($_COOKIE['user']))){
      return true;
    }else{
      return false;
    }
  }

  /**
   * If this user exists - login, otherwise return false
   * 
   * @param   string  email
   * @param   string  password
   * @return  boolean
   */
  public function login($email, $password)
  {
    $user = DB::findOne('user','email = :email AND password = :password', 
      array('email' => $email, 'password' => sha1($password)
    ));
    
    if(!$user){
      return false;
    }else{
      $user->keycode = sha1($email.$user->id.'this is my very-very-very secret code'.time());
    
      DB::store($user);

      setcookie('user', $user->keycode, time()+60*60*24*30, '/');

      return true;
    }
  }

  /**
   * Delete cookie
   * 
   */
  public function logout()
  {
    if(!isset($_COOKIE['user'])){
      return false;
    }

    setcookie('user', "", time() - 3600, '/');

    return true;
  }
}



  