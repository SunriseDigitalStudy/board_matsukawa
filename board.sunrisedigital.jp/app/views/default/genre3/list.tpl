{extends file='default/base.tpl'}
{block title append} ジャンルリスト{/block}
{block main_contents}

    <h3>ジャンル一覧表示</h3> 
    {foreach $genre_list as $genre}
        <a href="/thread3/{$genre->getId()}/list">{$genre->getName()}</a>  <br/>  
    {/foreach}
    <br/> 
    <br/> 
    <br/> 

    <h3>検索フォーム</h3>
    <form method="get" action="/search/list"> 
        ジャンルを選択<br/>
        <label>
            <input type="radio" name="radio" value=null>何も選択しない
        </label>
        <br/>
        {foreach $genre_list as $genre}
            <label>
                <input type="radio" name="radio" value={$genre->getId()}>
                {$genre->getName()}
            </label>
            <br/>  
        {/foreach}
        
        <br/>
        タグを選択<br/>
        {foreach $tab_list as $tab}
            <label>
                <input type="checkbox" name="checkbox[]" value={$tab->getId()}>
                {$tab->getName()}  
            </label>
            <br/>  
        {/foreach}
        <br/> 
        <br/>
        <input type="submit" value="検索する">
    </form>

{/block}