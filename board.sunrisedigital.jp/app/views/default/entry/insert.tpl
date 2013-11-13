{extends file='default/base.tpl'}
{block title append} 書き込み{/block}
{block main_contents}
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">書き込み</h3>
  </div>
  <div class="panel-body">
    {$form->renderStartTag() nofilter}
      <div class="form-group">
        {$form.thread_id->setLabel('スレッドＩＤ')->renderLabel() nofilter}
        {$form.thread_id->render([class=>"form-control", placeholder=>$form.thread_id->getLabel()]) nofilter}
      </div>
      <div class="form-group">
        {$form.account_id->setLabel('アカウントＩＤ')->renderLabel() nofilter}
        {$form.account_id->render([class=>"form-control", placeholder=>$form.account_id->getLabel()]) nofilter}
      </div>
      <div class="form-group">
        {$form.body->setLabel('内容')->renderLabel() nofilter}
        {$form.body->render([class=>"form-control", placeholder=>$form.body->getLabel()]) nofilter}
      </div>
      <div class="text-center">
        <input type="submit" name="submit" value="保存" class="btn btn-success">
      </div>
    </form>
  </div>
</div>
{/block}