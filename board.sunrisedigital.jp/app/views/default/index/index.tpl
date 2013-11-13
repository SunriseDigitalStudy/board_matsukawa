<!doctype html>
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
  {block css}{/block}
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  {block js}{/block}
  <title>３ちゃんねる {block title}{/block}</title>
</head>
<body>
  <header class="navbar navbar-inverse">{$sdx_user = $sdx_context->getUser()}
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="/"><i class="fa fa-comments-o text-warning"></i>３ちゃんねる</a>
      </div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown{if $sdx_user->hasId()} has-id{/if}">
            {*<a href="#" class="dropdown-toggle" data-toggle="dropdown">・・
              <i class="fa fa-user fa-lg"></i> <b class="caret"></b>
            </a>*}
{*            <ul class="dropdown-menu">　　　→ドロップダウンメニュー*}
            {*  {if $sdx_user->hasId()}
{*{*                  {$sdx_user->getLoginId()}
{$sdx_context->getVar('signed_account')->getName()}*}
 {*             <li class="dropdown-header">{$sdx_context->getVar('signed_account')->getName()}</li>　　　
              <li><a href="/secure/logout"><i class="fa fa-sign-out"></i> ログアウト</a></li>
              {else}
              <li><a href="/account/create"><i class="fa fa-plus-square"></i> ユーザー登録</a>
              </li>
              <li><a href="/secure/login"><i class="fa fa-sign-in"></i> ログイン</a></li>
              {/if}*}
           {* </ul>*}
          </li>
        </ul>
      </div>
    </div>
  </header>
  <section>
    <div class="container">
        <div><a href="http://dev.board.sunrisedigital.jp/genre/list"><p style="font-size:1500%"color="black">３ちゃんねる入口</p></a></div>
{* 中央寄せ      <div align="center"><a href="http://board.sunrisedigital.jp/thread1/itiran"><FONT size="20">いらっしゃい</FONT></a></div>*}
{*    <div><a href="index.htm"><img src="/home/source/sites/board/board.sunrisedigital.jp/web/img" alt="サンプル"></a></div>*}
    </div>
  </section>
</body>
</html>














{*{extends file='default/base.tpl'}
{block title}トップページ{/block}
 
{block main_contents}
<div><a href="index.htm"><img src="board.sunrisedigital.jp/web/img/img_795341_22826816_0" alt="サンプル"></a></div>
{/block}*}

