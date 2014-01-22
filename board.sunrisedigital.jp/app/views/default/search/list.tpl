{extends file='default/base.tpl'}
{block title append} サーチリスト{/block}
{block main_contents}

  <div class="row">
    
    <div class="col-sm-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3>検索フォーム</h3>
        </div>
        <div class="panel-body point-delete">
          {$form->renderStartTag() nofilter}
          <h3><span class="label label-default">ジャンル選択</span></h3>
          {$form.genre_id->setDefaultEmptyChild('何も選択しない')->render() nofilter}
          <h3><span class="label label-default">タグ選択</span></h3>
          {$form.tag_ids->render() nofilter}
          <button type="submit" name="submit" value="submit" class="btn btn-success"><b>検索</b><i class="glyphicon glyphicon-hand-left"></i></button>
          <input class="btn btn-danger clearForm" type="button" value="リセット">
          </form>
        </div>
      </div>
    </div>
          
    <div class="col-sm-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3>検索結果(スレッド一覧)</h3>
        </div>
        <div class="panel-body">
          {if get_class($thread_list->getFirstRecord()) == Sdx_Null }
            <p style="font-size:200%">検索条件に一致するスレッドはありません</p><br/>
            <p><img src="/img/20081221231807.jpg" alt="やる夫3"></p>
          {else}
            <ul>
              {foreach $thread_list as $thread}
                <li>
                  <span style="font-size:130%"><a href="/entry3/{$thread->getId()}/list">{$thread->getTitle()}</a></span>
                  &nbsp;
                {if $thread->get('updated')}{$thread->getZendDate('updated')->get('yyyy年MM月dd日(E) HH時mm分ss秒')}{else}コメントは一件もありません{/if}
              </li>        
            {/foreach}
          </ul>
        {/if}
      </div>     
    </div>      
      <p style="font-size:200%"><img src="/img/20110224223407740.png" alt="やる夫2">自由に書き込んだらいいお</p>     
  </div>
      
</div>
      
<script>
  $(".clearForm").bind("click", function(){
          $(this.form).find(":checked").prop("checked", false);
  });
</script>
        
{/block} 