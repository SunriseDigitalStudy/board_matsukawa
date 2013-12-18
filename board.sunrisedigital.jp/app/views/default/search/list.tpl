{extends file='default/base.tpl'}
{block title append} サーチリスト{/block}
{block main_contents}
    
    <h3>検索フォーム</h3>
    <form method="get" name="form1" id="form1" action="/search/list"> 
        ジャンルを選択<br/>
        <label>
            <input type="radio" name="genre_id" value="">何も選択しない
        </label>
        <br/>
        {foreach $genre_list as $genre}
            <label>
                <input type="radio" name="genre_id" value={$genre->getId()} {if $genre->getId() == $genre_id}checked="checked"{/if}>
                {$genre->getName()}
            </label>
            <br/>  
        {/foreach}

        <br/>
        タグを選択<br/>
        {foreach $tag_list as $tag}
            <label>
                <input type="checkbox" name="tag_ids[{$tag->getId()}]"  value={$tag->getId()} {if $tag->getId() == $tag_ids[$tag->getId()]} checked="checked" {/if}>
                {$tag->getName()}  
            </label>
            <br/>  
        {/foreach}
        <br/> 
        <br/>
        <input type="submit" value="検索する">
    </form>        
        
    <h3>検索結果</h3>
    <div id="content"></div>
    
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
