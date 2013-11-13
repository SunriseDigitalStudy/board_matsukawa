{extends file='default/base.tpl'}
{block title append} ジャンル登録{/block}
{block main_contents}
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">ジャンル登録</h3>
  </div>
  <div class="panel-body">
    {$form->renderStartTag() nofilter}
      <div class="form-group">
        {$form.name->setLabel('ジャンルの名前')->renderLabel() nofilter}
        {$form.name->render([class=>"form-control", placeholder=>$form.name->getLabel()]) nofilter}
      </div>
      <div class="form-group">
        {$form.sequence->setLabel('番号')->renderLabel() nofilter}
        {$form.sequence->render([class=>"form-control", placeholder=>$form.sequence->getLabel()]) nofilter}
      </div>
      <div class="text-center">
        <input type="submit" name="submit" value="保存" class="btn btn-success">
      </div>
    </form>
  </div>
</div>
{/block}