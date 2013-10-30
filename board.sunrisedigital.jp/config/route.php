<?php
$cash_data = array (
  'routes' => '',
  'thread_default' => 
  array (
    'route' => '/thread/:thread_id/:action',
    'defaults' => 
    array (
      'module' => 'default',
      'controller' => 'thread',
      'action' => 'index',
    ),
    'reqs' => 
    array (
      'thread_id' => '[0-9]+',
    ),
  ),
);