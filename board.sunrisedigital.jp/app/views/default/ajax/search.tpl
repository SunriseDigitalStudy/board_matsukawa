<div hidden id="offset">{$thread_count}</div>
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
            <span id="back">＜</span><span id="page"></span><span id="next">＞</span>
            <ul id="aaa">
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


