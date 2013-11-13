{extends file='default/base.tpl'}
{block title append} ジャンルリスト{/block}
{block main_contents}
<ul>
  {foreach $genre_list as $genre}
  <li>
    <div class="account">
{*        <a href="index.htm"><img src="sample.gif" alt="サンプル"></a>*}
        {$genre->getId()}
        {$genre->getName()}
        
    </div>
    
  </li>
  {/foreach}
</ul>  
{/block}