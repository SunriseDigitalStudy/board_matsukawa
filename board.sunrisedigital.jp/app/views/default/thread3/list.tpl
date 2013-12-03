{extends file='default/base.tpl'}
{block title append} スレッドリスト{/block}
{block main_contents}
<ul>
  {foreach $threadt_list as $threadt}
  <li>
        <a href="/entry3/{$threadt->getId()}/list">{$threadt->getTitle()}</a>
        &nbsp;
        {if $threadt->get('updated')}{$threadt->getZendDate('updated')->get('yyyy年MM月dd日(E) HH時mm分ss秒')}{else}コメント：0{/if}
  </li>
  {/foreach}
</ul>  
{/block}