{extends file='default/base.tpl'}
{block title append} 書き込みリスト{/block}
{block main_contents}
  <h1>{$thread->getTitle()}</h1>
  </br>
  <ol>
    {foreach $entry_list as $entry}
      <li>
        {$account = $entry->getAccount()}       
        名前：{$account->getName()} &nbsp;
        日時：{$entry->getZendDate('created_at')->get('yyyy年MM月dd日(E) HH時mm分ss秒')}</br>
        <p class="newline">{$entry->getBody()|escape|nl2br nofilter}</p>
      </li>
    {/foreach}
  </ol>     


  {if $sdx_context->getUser()->hasId() == null}
    <font color="red"><b>ログインをしないとコメントはできません</b></font>
    {$form->renderStartTag() nofilter}  
    {$form.body->setLabel('コメント')->renderLabel() nofilter}
    {$form.body->render([class=>"form-control", placeholder=>"ゆっくりしていってね∩( ´∀｀)∩ヽ(〃´∀｀〃)ﾉ", disabled=>"true"]) nofilter}       
    <input type="submit" name="submit" value="送信" disabled="true" class="btn btn-success">
    <a class="btn btn-primary" href="http://board.sunrisedigital.jp/search/list">検索ページに戻る</a>
  </form>
{else} 
  {$form->renderStartTag() nofilter}  
  {$form.body->setLabel('コメント')->renderLabel() nofilter}
  {$form.body->render([class=>"form-control", placeholder=>"ゆっくりしていってね∩( ´∀｀)∩ヽ(〃´∀｀〃)ﾉ"]) nofilter}
  {$form.body->renderError() nofilter}
  <input type="submit" name="submit" value="送信" id='bottom' class="btn btn-success">
  <a class="btn btn-primary" href="http://board.sunrisedigital.jp/search/list">検索ページに戻る</a>
</form>
{/if}
<div id="back-to-top" style="position: fixed; right: 5px; bottom: 5px; font-size:400%"><a href="#">㊤</a></div>
<script>
  $(function(){
    //#back-to-topを消す
    $('#back-to-top').hide();
    //スクロールが十分されたら、#back-to-topを表示、スクロールが戻ったら非表示
    $(window).scroll(function(){
      if($(this).scrollTop() > 60){
        $('#back-to-top').fadeIn();
      }else{
        $('#back-to-top').fadeOut();        
      }
    });
    //#back-to-topがクリックされたら上に戻る
    $('#back-to-top a').click(function (){
      $('body').animate({
        scrollTop:0
    }, 500);
    return false;
    });
  });
</script>
{/block}
