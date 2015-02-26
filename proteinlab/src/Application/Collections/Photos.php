<?php

/**
 * Photos collection class
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

class Photos extends CollectionsParent{
  
  /**
   * @var  $table  string
   */
  public $table = 'photo';

  /**
   * Find and return a lot of items by their id's
   * 
   * @param  int[]  $ids
   * @return Application\Models\Photo[]      
   */
  public function findByProductIds(array $ids)
  {
    return DB::findAll($this->table, 'product_id IN ('.implode(',', $ids).')');
  }

  public function upload()
  {
    
    $storage = new \Upload\Storage\FileSystem($_SERVER['DOCUMENT_ROOT'].'/uploads/products/');
    $file    = new \Upload\File('photo', $storage);

    $new_filename = uniqid();
    $file->setName($new_filename);

    $file->addValidations(array(
      new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg')),
      new \Upload\Validation\Size('5M')
    ));

    try {
      $file->upload();
    } catch (\Exception $e) {
      echo $file->getErrors();
    }

    return array(
      'name' => $file->getNameWithExtension(),
    );
  }
}