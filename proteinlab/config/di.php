<?php
// --------------------------- Controllers --------------------------- //
$slim->container->singleton('indexController', function () use($slim){
  return new \Application\Controllers\Index($slim);
});
$slim->container->singleton('catalogueController', function () use($slim){
  return new \Application\Controllers\Catalogue($slim);
});
$slim->container->singleton('orderController', function () use($slim){
  return new \Application\Controllers\Order($slim);
});
$slim->container->singleton('blogController', function () use($slim){
  return new \Application\Controllers\Blog($slim);
});
$slim->container->singleton('adminController', function () use($slim){
  return new \Application\Controllers\Admin($slim);
});

// --------------------------- Collections --------------------------- //
$slim->container->singleton('Bookings', function () use($slim){
  return new \Application\Collections\Bookings($slim);
});
$slim->container->singleton('Bookings2Product', function () use($slim){
  return new \Application\Collections\Bookings2Product($slim);
});
$slim->container->singleton('Categorys', function () use($slim){
  return new \Application\Collections\Categorys($slim);
});
$slim->container->singleton('Comments', function () use($slim){
  return new \Application\Collections\Comments($slim);
});
$slim->container->singleton('Contents', function () use($slim){
  return new \Application\Collections\Contents($slim);
});
$slim->container->singleton('Feedbacks', function () use($slim){
  return new \Application\Collections\Feedbacks($slim);
});
$slim->container->singleton('Manufacturers', function () use($slim){
  return new \Application\Collections\Manufacturers($slim);
});
$slim->container->singleton('Photos', function () use($slim){
  return new \Application\Collections\Photos($slim);
});
$slim->container->singleton('Posts', function () use($slim){
  return new \Application\Collections\Posts($slim);
});
$slim->container->singleton('Posts2Tags', function () use($slim){
  return new \Application\Collections\Posts2Tags($slim);
});
$slim->container->singleton('Products', function () use($slim){
  return new \Application\Collections\Products($slim);
});
$slim->container->singleton('Relateds', function () use($slim){
  return new \Application\Collections\Relateds($slim);
});
$slim->container->singleton('Users', function () use($slim){
  return new \Application\Collections\Users($slim);
});
$slim->container->singleton('Tags', function () use($slim){
  return new \Application\Collections\Tags($slim);
});
$slim->container->singleton('Top8s', function () use($slim){
  return new \Application\Collections\Top8s($slim);
});
$slim->container->singleton('Weights', function () use($slim){
  return new \Application\Collections\Weights($slim);
});
$slim->container->singleton('Widgets', function () use($slim){
  return new \Application\Collections\Widgets($slim);
});