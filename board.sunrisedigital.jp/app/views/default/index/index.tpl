<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
  <link href='http://fonts.googleapis.com/css?family=Montserrat+Subrayada' rel='stylesheet' type='text/css'>
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
    .container p{
        font-family: 'Montserrat Subrayada', sans-serif;
        
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
    </div>
  </header>
  <section>
    <div class="container">
        <div><a href="/json/list"><p style="font-size:1500%"color="black" >3ch</p></a></div>
    </div>
  </section>
</body>
</html>














