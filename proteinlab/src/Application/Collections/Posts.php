<?php

/**
 * Posts collection class
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

class Posts extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'post';

  public $paginationLimit = 5;

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAll()
  {
    return $this->prepare(DB::findAll($this->table));
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getForHomePage()
  {
    return $this->prepare(DB::findAll($this->table, 'ORDER BY time DESC LIMIT 3'));
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getByPage($page = 0)
  {
    $page = $page > 0 ? --$page : 0;

    return $this->prepare(DB::findAll($this->table, 'ORDER BY time DESC LIMIT :page, '.$this->paginationLimit, array('page' =>  $page * $this->paginationLimit )));
  }
  
  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getPaginationCount()
  {
    return ceil(DB::count($this->table) / $this->paginationLimit);
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getTagsPaginationCount($slug)
  {
    $tag = $this->slim->Tags->getOneBySlug($slug);

    if(!$tag){
      return array();
    }

    $p2tIds = $this->slim->Posts2Tags->findByTagId($tag->id);

    if(empty($p2tIds)){
      return array();
    }
    
    $ids = array_map(function($one){ return $one->post_id;}, $p2tIds);

    return ceil(DB::count($this->table, 'id IN ('.implode(',', $ids).')') / $this->paginationLimit);
  }

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByIds(array $ids)
  {
    return $this->prepare(DB::findAll($this->table, 'id IN ('.implode(',', $ids).')'));
  }

  public function getByTag($slug, $page = 0)
  {
    $tag = $this->slim->Tags->getOneBySlug($slug);

    if(!$tag){
      return array();
    }

    $p2tIds = $this->slim->Posts2Tags->findByTagId($tag->id);

    if(empty($p2tIds)){
      return array();
    }
   
    $ids = array_map(function($one){ return $one->post_id;}, $p2tIds);
    $page = $page > 0 ? --$page : 0;

    return $this->prepare(DB::findAll($this->table, 'id IN ('.implode(',', $ids).') ORDER BY time DESC LIMIT :page, '.$this->paginationLimit, array('page' => $page * $this->paginationLimit )));
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getOneById($id)
  {
    return reset($this->prepare(array(DB::load($this->table, $id))));
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getOneBySlug($slug)
  {
    return reset($this->prepare(array(DB::findOne($this->table, 'slug = ?', array($slug)))));
  }
  

  public function prepare(array $posts)
  {
    if(empty($posts)){
      return $posts;
    }

    $ids = array_map(function($post){ return $post->id; }, $posts);
    $p2t = $this->slim->Posts2Tags->findByPostIds($ids);
    $comments = $this->slim->Comments->findByPostIds($ids);
    $tags = $this->slim->Tags->getAll();

    foreach ($posts as $key => $post){
      $postTags = array_map(function($onePostTag){ return $onePostTag->tag_id; },
        array_filter($p2t, function($one) use($post){ return $one->post_id == $post->id; }
      ));

      
      $posts[$key]['tags'] = array_filter($tags, function($tag) use($postTags){ return in_array($tag->id, $postTags);});
      $posts[$key]['comments'] = array_filter($comments, function($comment) use($post){ return $comment->pid == $post->id; });

    }


    return $posts;
  }

  public function add(array $values)
  {
    $post = DB::dispense($this->table);
    $id = DB::store($post);

    return $this->edit($id, $values);
  }

  public function edit($id, array $values)
  {
    $post = DB::load($this->table, $id);
    

    $post->title        = $values['title'];
    $post->body         = $values['body'];
    $post->short        = $values['short'];
    $post->shortest     = $values['shortest'];
    $post->keywords     = $values['keywords'];
    $post->description  = $values['description'];
    
    $post->slug = \Application\Models\Translit::str2url($post->title);
    $post->time = time();

    DB::store($post);

    $tags2Posts = $this->slim->Posts2Tags->findByPostIds(array($post->id));
    DB::trashAll($tags2Posts); 

    if(is_array($values['tags'])){
      foreach($values['tags'] as $tag) {        
        $tags2PostsBean = DB::dispense($this->slim->Posts2Tags->table);

        $tags2PostsBean->post_id = $post->id;
        $tags2PostsBean->tag_id =  $tag;

        DB::store($tags2PostsBean);
      }
    }

    return $post;
  } 

  public function delete($id)
  {
    $post = DB::load($this->table, $id);

    $tags2Posts = $this->slim->Posts2Tags->findByTagIds(array($post->id));
    DB::trashAll($tags2Posts); 

    return DB::trash($post); 
  }
}