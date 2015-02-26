<?php

// Only invoked if mode is "production"
$slim->configureMode('production', function () use ($slim) {
  $slim->config(array(
    'log.enable'     => true,
    'debug'          => false,
    'templates.path' => '../proteinlab/view/'
  ));
});

// Only invoked if mode is "development"
$slim->configureMode('development', function () use ($slim) {
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  $slim->config(array(
    'log.enable'      => false,
    'debug'           => true,
    'templates.path'  => '../proteinlab/view/'
  ));
});
