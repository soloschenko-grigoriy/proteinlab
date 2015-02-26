<?php

/**
 * Products collection class
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

class Products extends CollectionsParent{

  /**
   * @var  $table  string
   */
  public $table = 'product';

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAll($full = false)
  {
    $products = DB::findAll($this->table, 'ORDER BY name');

    if(!$products || count($products) == 0){
      return array();
    }

    return $this->prepare($products, $full);
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getAllInNativeOrder($full = false)
  {
    $products = DB::findAll($this->table);

    if(!$products || count($products) == 0){
      return array();
    }

    return $this->prepare($products, $full);
  }

  /**
   * Find and return all products by category id
   * 
   * @param  int $categoryId
   * @return Application\Models\Product[]         
   */
  public function getByIds(array $ids)
  {
    $products = DB::findAll($this->table, 'id IN ('.implode(',', $ids).') ORDER BY name');

    if(!$products || count($products) == 0){
      return array();
    }


    return  $this->prepare($products);
  }

  /**
   * Find and return all products by category id
   * 
   * @param  int $categoryId
   * @return Application\Models\Product[]         
   */
  public function getByCategory($categoryId = null)
  {
    if(!$categoryId || (int) $categoryId < 1){
      return array();
    }else{
      $products = DB::findAll($this->table, 'category_id = :categoryId ORDER BY name', array('categoryId' => (int) $categoryId));
    }

    if(!$products || count($products) == 0){
      return array();
    }


    return  $this->prepare($products);
  }

  /**
   * Find and return all products by manufacturer id
   * 
   * @param  int $categoryId
   * @return Application\Models\Product[]         
   */
  public function getByManufacturer($manufacturerId = null)
  {
    if(!$manufacturerId || (int) $manufacturerId < 1){
      return array();
    }else{
      $products = DB::findAll($this->table, 'manufacturer_id = :manufacturerId ORDER BY name', array('manufacturerId' => (int) $manufacturerId));
    }

    if(!$products || count($products) == 0){
      return array();
    }


    return  $this->prepare($products);
  }


  /**
   * Prepare each product
   * 
   * @param  Application\Models\Product[] $products
   * @return Application\Models\Product[]         
   */
  public function prepare(array $products, $full = false)
  {    
    $products = $this->prepareRelative($products, 'photos');
    $products = $this->prepareRelative($products, 'weights');
    $products = $this->prepareRelative($products, 'related');

    if($full === true){
      $products = $this->prepareDirect($products, 'category');
      $products = $this->prepareDirect($products, 'manufacturer');
    }
    
    return $products;
  }

  public function prepareDirect(array $products, $itemsKey)
  {
    if($itemsKey == 'category'){
      $items = $this->slim->Categorys->getByIds(array_unique(array_map(function($one){  return $one->category_id; }, $products)));
    }else if($itemsKey == 'manufacturer'){
      $items = $this->slim->Manufacturers->getByIds(array_map(function($one){  return $one->manufacturer_id; }, $products));
    }else{
      return $products;
    }
    foreach ($products as $key=>$product) {
      if($itemsKey == 'category'){
        $filtered = array_filter($items, function($item) use ($product) { 
          return $item['id'] == $product->category_id; 
        });
      }else{
        $filtered = array_filter($items, function($item) use ($product) { 
          return $item['id'] == $product->manufacturer_id; 
        });
      }
      
      $products[$key][$itemsKey] = reset($filtered);
    }

    return $products;
  }

  /**
   * Prepare each product
   * 
   * @param  Application\Models\Product[] $products
   * @param  string                       $key
   * 
   * @return Application\Models\Product[]         
   */
  public function prepareRelative(array $products, $itemsKey)
  {
    if($itemsKey == 'photos'){
      $table = $this->slim->Photos;
    }else if($itemsKey == 'weights'){
      $table = $this->slim->Weights;
    }else if($itemsKey == 'related'){
      $table = $this->slim->Relateds;
    }else{
      return array();
    }
    
    $items = $table->findByProductIds(array_map(function($one){  return $one->id; }, $products));
    // $items = DB::exportAll($items);


    $min = 0;
    foreach ($products as $key=>$product) {
      $filtered = array_filter($items, function($item) use ($product) { 
        return $item['product_id'] == $product->id; 
      });

      if($itemsKey == 'photos'){
        $products[$key]['img'] = reset($filtered);
      }else if($itemsKey == 'weights'){
        $products[$key]['minPrice'] = min(array_map(function($one){ return $one['price']; }, $filtered));
        $products[$key]['firstWeight'] = reset($filtered);
      }
      
      $products[$key][$itemsKey] = array_values($filtered);
    }
    
    return $products;
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getOneById($id)
  {
    $product = DB::load($this->table, $id);

    if(!$product){
      return null;
    }

    $products = $this->prepare(array($product));

    return reset($products);
  }

  /**
   * Find and return all comments by pagination
   * 
   * @return  \Application\Collections\Products
   */ 
  public function getOneBySlug($slug)
  {
    $product = DB::findOne($this->table, 'slug = ?', array($slug));

    if(!$product){
      return null;
    }

    $products = $this->prepare(array($product));
    $product = reset($products);

    if(count($product->related) > 0){
      $ids = array_map(function($item){ return $item['related_id']; }, $product->related);
      $product->related = $this->getByIds(array_unique($ids));
    }
    
    return $product;
  }
  public function add(array $values)
  {
    $product = DB::dispense($this->table);
    $id = DB::store($product);

    return $this->edit($id, $values);
  }

  public function edit($id, array $values)
  {
    $product = DB::load($this->table, $id);
    

    $product->name             = $values['name'];
    $product->info             = $values['info'];
    $product->description      = $values['desc'];
    $product->category_id      = $values['category'];
    $product->manufacturer_id  = $values['manufacturer'];
    $product->keywords         = $values['keywords'];
    $product->meta_description = $values['meta_description'];
    
    $manufacturer = $this->slim->Manufacturers->getOneById($product->manufacturer_id);
    $product->slug = \Application\Models\Translit::str2url($product->name.' '.$manufacturer->name);

    DB::store($product);

    $weights = $this->slim->Weights->findByProductIds(array($product->id));
    DB::trashAll($weights); 

    if(is_array($values['weights'])){
      foreach($values['weights'] as $weight) {
        if(empty($weight['name'])){
          continue;
        }
        
        $weightBean = DB::dispense($this->slim->Weights->table);

        $weightBean->name         = $weight['name'];
        $weightBean->type         = $weight['type'];
        $weightBean->price        = $weight['price'];
        $weightBean->product_id   = $product->id;

        DB::store($weightBean);
      }
    }

    $photos = $this->slim->Photos->findByProductIds(array($product->id));
    foreach($photos as $photo){
      $file = $_SERVER['DOCUMENT_ROOT'].'/uploads/products/'.$photo->src;
      if(file_exists($file) && !in_array($photo->src, $values['photos'])){
        unlink($file);
      }      
    }

    DB::trashAll($photos); 

    if(is_array($values['photos'])){
      foreach ($values['photos'] as $photo){
        if(empty($photo)){
          continue;
        }
        
        $photoBean = DB::dispense($this->slim->Photos->table);

        $photoBean->name        = $product->name.' '.$manufacturer->name;
        $photoBean->src         = $photo;
        $photoBean->product_id  = $product->id;

        DB::store($photoBean);
      }
    }

    $relateds = $this->slim->Relateds->findByProductIds(array($product->id));
    DB::trashAll($relateds); 

    if(is_array($values['related'])){
      foreach ($values['related'] as $related) {
        $relatedBean = DB::dispense($this->slim->Relateds->table);

        $relatedBean->related_id   = $related;
        $relatedBean->product_id   = $product->id;

        DB::store($relatedBean);
      }
    }

    return $product;
  } 

  public function delete($id)
  {
    $product = DB::load($this->table, $id);

    $weights = $this->slim->Weights->findByProductIds(array($product->id));
    DB::trashAll($weights); 

    $photos = $this->slim->Photos->findByProductIds(array($product->id));
    foreach($photos as $photo){
      $file = $_SERVER['DOCUMENT_ROOT'].'/uploads/products/'.$photo->src;
      if(file_exists($file)){
        unlink($file);
      }      
    }

    DB::trashAll($photos); 

    return DB::trash($product); 
  }
}