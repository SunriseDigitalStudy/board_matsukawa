{extends file='default/base.tpl'}
{block title append} サーチリスト{/block}
{block main_contents}


  {$form->renderStartTag() nofilter}
  <div class="form-group">
    {$form.genre_id->setDefaultEmptyChild('何も選択しない')->render() nofilter}
    <br/>
    {$form.tag_ids->render() nofilter}
    <br/>
    <button type="submit" class="btn">検索<i class="glyphicon glyphicon-hand-left"></i></button>
  </div>
</form>

<h3>検索結果</h3>
{if get_class($thread_list->getFirstRecord()) == Sdx_Null }
  検索条件に一致するスレッドはありません<br/>
  <a href="/search/list">戻る</a>
{else}
  <ul>
    {foreach $thread_list as $thread}
      <li>
        <span><a href="/entry3/{$thread->getId()}/list">{$thread->getTitle()}</a></span>
        &nbsp;
      {if $thread->get('updated')}{$thread->getZendDate('updated')->get('yyyy年MM月dd日(E) HH時mm分ss秒')}{else}コメントは一件もありません{/if}
    </li>        
  {/foreach}
</ul>
{/if}

{/block} 