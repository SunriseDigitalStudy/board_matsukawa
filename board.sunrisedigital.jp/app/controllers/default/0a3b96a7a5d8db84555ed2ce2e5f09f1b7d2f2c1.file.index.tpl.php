<?php /* Smarty version Smarty-3.1-DEV, created on 2013-11-07 16:13:01
         compiled from "./app/views/default/index/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2076259924527b3d7d16c1c3-54150057%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0a3b96a7a5d8db84555ed2ce2e5f09f1b7d2f2c1' => 
    array (
      0 => './app/views/default/index/index.tpl',
      1 => 1383801323,
      2 => 'file',
    ),
    'acd1a8dbe58351abacd4c2fa96bf2b4a2c259fa1' => 
    array (
      0 => './app/views/default/base.tpl',
      1 => 1383808131,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2076259924527b3d7d16c1c3-54150057',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sdx_context' => 0,
    'sdx_user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_527b3d7d2479f1_63968616',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_527b3d7d2479f1_63968616')) {function content_527b3d7d2479f1_63968616($_smarty_tpl) {?><!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
  <style>
    .sdx_error{
      font-size: 12px;
      margin: 0;
      padding: 0;
      font-weight: bold;
      list-style: none;
      color: #b94a48;
    }
    .sdx_error > li:before{
      content: "\f14a";
      font-family: FontAwesome;
    }
  </style>
  
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  
  <title>Board </title>
</head>
<body>
  <header class="navbar navbar-inverse"><?php $_smarty_tpl->tpl_vars['sdx_user'] = new Smarty_variable($_smarty_tpl->tpl_vars['sdx_context']->value->getUser(), null, 0);?>
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="/"><i class="fa fa-comments-o text-warning"></i> Board</a>
      </div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown<?php if ($_smarty_tpl->tpl_vars['sdx_user']->value->hasId()){?> has-id<?php }?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user fa-lg"></i> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
              <?php if ($_smarty_tpl->tpl_vars['sdx_user']->value->hasId()){?>
              <li class="dropdown-header"><?php echo $_smarty_tpl->smarty->registered_filters[Smarty::FILTER_VARIABLE][Sdx_Smarty_escape][0]->escape($_smarty_tpl->tpl_vars['sdx_context']->value->getVar('signed_account')->getName(),$_smarty_tpl);?>
</li>
              <li><a href="/secure/logout"><i class="fa fa-sign-out"></i> ログアウト</a></li>
              <?php }else{ ?>
              <li><a href="/account/create"><i class="fa fa-plus-square"></i> ユーザー登録</a>
              </li>
              <li><a href="/secure/login"><i class="fa fa-sign-in"></i> ログイン</a></li>
              <?php }?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <section>
    <div class="container">
    
    </div>
  </section>
</body>
</html><?php }} ?>