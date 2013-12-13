{*{extends file='default/base.tpl'}*}
{block title append} サーチリスト{/block}
{block main_contents}
    {if get_class($thread_list->getFirstRecord()) == Sdx_Null }
        検索条件に一致するスレッドはありません<br/>
        <a href="/genre3/list">戻る</a>
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
