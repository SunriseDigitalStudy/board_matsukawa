{extends file='default/base.tpl'}
{block title append} スレッド登録{/block}
{block main_contents}
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">スレッド登録</h3>
  </div>
  <div class="panel-body">
    {$form->renderStartTag() nofilter}
      <div class="form-group">
        {$form.genre_id->setLabel('ジャンルID')->renderLabel() nofilter}
        {$form.genre_id->render([class=>"form-control", placeholder=>$form.genre_id->getLabel()]) nofilter}
      </div>
      <div class="form-group">
        {$form.title->setLabel('タイトル')->renderLabel() nofilter}
        {$form.title->render([class=>"form-control", placeholder=>$form.title->getLabel()]) nofilter}
      </div>
      <div class="text-center">
        <input type="submit" name="submit" value="保存" class="btn btn-success">
      </div>
    </form>
  </div>
</div>
{/block}