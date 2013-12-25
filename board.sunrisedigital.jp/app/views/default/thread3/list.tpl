{extends file='default/base.tpl'}
{block title append} スレッドリスト{/block}
{block main_contents}
  <ul>
    {foreach $threadt_list as $threadt}
      <li>
        <span><a href="/entry3/{$threadt->getId()}/list">{$threadt->getTitle()}</a></span>
        &nbsp;
      {if $threadt->get('updated')}{$threadt->getZendDate('updated')->get('yyyy年MM月dd日(E) HH時mm分ss秒')}{else}コメントは一件もありません{/if}
    </li>
  {/foreach}
</ul>  
{/block}