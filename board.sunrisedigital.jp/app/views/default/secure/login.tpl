<!DOCTYPE html>
{extends file='default/base.tpl'}
{block main_contents}
<html>
  <head>
    <meta charset="UTF-8">
    <title>Secure Signin</title>
  </head>
  <body>
    <div class="panel panel-default">
      <div class="panel-heading">ログイン</div>
      <div class="panel-body">
        {if $failed}<div class="alert alert-danger">IDかパスワードが違います</div>{/if}
        {$form->renderStartTag() nofilter}
          <div class="form-group{if $form.login_id->hasError()} has-error{/if}">
            {$form.login_id->setLabel('ログインID')->renderLabel() nofilter}
            {$form.login_id->render([class=>"form-control", placeholder=>$form.login_id->getLabel()]) nofilter}
            {$form.login_id->renderError() nofilter}
          </div>
          <div class="form-group{if $form.password->hasError()} has-error{/if}">
            {$form.password->setLabel('パスワード')->renderLabel() nofilter}
            {$form.password->render([class=>"form-control", placeholder=>$form.password->getLabel()]) nofilter}
            {$form.password->renderError() nofilter}
          </div>
          <div>{$form[$auto_login_cookie]->setLabel("次回は自動でログイン")->renderWithLabel() nofilter}</div>
          <div class="text-center">
            <input type="submit" name="submit" value="ログイン" class="btn btn-primary" >
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
{/block}