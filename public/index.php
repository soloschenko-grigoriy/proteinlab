<?php
require '../proteinlab/vendor/autoload.php';
require '../proteinlab/vendor/redbean/rb.php';


$slim = new \Slim\Slim(array(
  'mode' => 'production',//development production
));

// Load configs
$configs = glob('../proteinlab/config/*.php');
foreach ($configs as $config) {
  require $config;
}


// -------------------------------- Frontend -------------------------------- //
$slim->notFound(function () use ($slim) {
  $slim->render('frontend/layout.phtml', $slim->indexController->page404());
});

$slim->error(function (\Exception $e) use ($slim) {
    $slim->render('frontend/layout.phtml', $slim->indexController->page500());
});


$slim->get('/', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->indexController->start());
});

$slim->get('/about', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->indexController->about());
});
$slim->get('/contacts', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->indexController->contacts());
});
$slim->get('/delivery', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->indexController->delivery());
});
$slim->get('/rules', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->indexController->rules());
});
$slim->get('/testimonials', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->indexController->testimonials());
});
$slim->post('/contacts/send', function () use($slim){
  echo json_encode($slim->indexController->contactsSend());
});

$slim->get('/category/', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->catalogueController->general('category'));
});
$slim->get('/manufacturer/', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->catalogueController->general('manufacturer'));
});
$slim->get('/category/:category', function ($category) use($slim){
  $slim->render('frontend/layout.phtml', $slim->catalogueController->start('category', $category));
});
$slim->get('/manufacturer/:manufacturer', function ($manufacturer) use($slim){
  $slim->render('frontend/layout.phtml', $slim->catalogueController->start('manufacturer', $manufacturer));
});
$slim->get('/item/:item', function ($slug) use($slim){
  $slim->render('frontend/layout.phtml', $slim->catalogueController->item($slug));
});
$slim->post('/item/comment', function () use($slim){
  echo json_encode($slim->catalogueController->comment());
});

$slim->get('/order', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->orderController->start());
});

$slim->post('/order/confirm', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->orderController->confirm());
});

$slim->post('/order/add', function () use($slim){
  echo json_encode($slim->orderController->add());
});
$slim->post('/order/increase', function () use($slim){
  echo json_encode($slim->orderController->increase());
});
$slim->post('/order/decrease', function () use($slim){
  echo json_encode($slim->orderController->decrease());
});
$slim->post('/order/remove', function () use($slim){
  echo json_encode($slim->orderController->remove());
});
$slim->post('/order/clear', function () use($slim){
  echo json_encode($slim->orderController->clear());
});
$slim->post('/order/quick', function () use($slim){
  echo json_encode($slim->orderController->quick());
});

$slim->get('/blog', function () use($slim){
  $slim->render('frontend/layout.phtml', $slim->blogController->start());
});
$slim->get('/blog/page/:page', function ($page) use($slim){
  $slim->render('frontend/layout.phtml', $slim->blogController->start($page));
});
$slim->get('/blog/item/:slug', function ($slug) use($slim){
  $slim->render('frontend/layout.phtml', $slim->blogController->item($slug));
});
$slim->get('/blog/tags/:slug', function ($slug) use($slim){
  $slim->render('frontend/layout.phtml', $slim->blogController->tags($slug));
});
$slim->get('/blog/tags/:slug/page/:page', function ($slug, $page) use($slim){
  $slim->render('frontend/layout.phtml', $slim->blogController->tags($slug, $page));
});
$slim->post('/blog/comment', function () use($slim){
  echo json_encode($slim->blogController->comment());
});

// -------------------------------- Backend -------------------------------- //
$slim->get('/admin', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->start());
});

$slim->get('/admin/login', function () use($slim){
  $slim->render('backend/login.phtml', $slim->adminController->login());
});
$slim->post('/admin/login', function () use($slim){
  $slim->render('backend/login.phtml', $slim->adminController->login());
});
$slim->get('/admin/logout', function () use($slim){
  $slim->render('backend/login.phtml', $slim->adminController->logout());
});
$slim->get('/admin/main', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->main());
});
$slim->get('/admin/about', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->about());
});
$slim->get('/admin/contacts', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->contacts());
});
$slim->get('/admin/products', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->products());
});
$slim->get('/admin/one', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->one());
});
$slim->get('/admin/one/:id', function ($id) use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->one($id));
});
$slim->post('/admin/one', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->saveOne());
});
$slim->post('/admin/one/:id', function ($id) use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->saveOne($id));
});
$slim->delete('/admin/one/:id', function ($id) use($slim){
  echo json_encode($slim->adminController->deleteOne($id));
});
$slim->post('/admin/upload', function () use($slim){
  echo json_encode($slim->adminController->upload());
});
$slim->get('/admin/categories', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->categories());
});
$slim->get('/admin/manufactures', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->manufactures());
});
$slim->get('/admin/top', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->top());
});
$slim->get('/admin/articles', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->articles());
});
$slim->get('/admin/articles/one', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->articlesOne());
});
$slim->get('/admin/articles/one/:id', function ($id) use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->articlesOne($id));
});
$slim->post('/admin/articles/one', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->saveArticlesOne());
});
$slim->post('/admin/articles/one/:id', function ($id) use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->saveArticlesOne($id));
});
$slim->delete('/admin/articles/one/:id', function ($id) use($slim){
  echo json_encode($slim->adminController->deleteArticlesOne($id));
});
$slim->get('/admin/tags', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->tags());
});
$slim->get('/admin/orders', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->orders());
});
$slim->post('/admin/orders/:id', function ($id) use($slim){
  echo json_encode($slim->adminController->changeOrder($id));
});
$slim->get('/admin/feedback', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->feedback());
});

$slim->get('/admin/postComments', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->postComments());
});
$slim->get('/admin/itemComments', function () use($slim){
  $slim->render('backend/layout.phtml', $slim->adminController->itemComments());
});
$slim->post('/admin/comment/:id', function ($id) use($slim){
  echo json_encode($slim->adminController->saveComment($id));
});

$slim->get('/admin/parser', function () use($slim){
  $slim->adminController->parser();
});

session_start();

$slim->run();