{extends file='default/base.tpl'}
{block title append} スレッドリスト{/block}
{block main_contents}
<ul>
  {foreach $threadt_list as $threadt}
  <li>
        <a href="/entry3/{$threadt->getId()}/list">{$threadt->getTitle()}</a>
  </li>
  {/foreach}
</ul>  
{/block}