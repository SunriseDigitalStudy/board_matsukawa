{extends file='default/base.tpl'}
{block title append} ジャンルリスト{/block}
{block main_contents}
<ul>
  {foreach $genre_list as $genre}
  <li>
    <div class="account">

        <a href="/thread2/list{$genre->getId()}">{$genre->getName()}</a>        
        
    </div>    
  </li>
  {/foreach}
</ul>  
{/block}