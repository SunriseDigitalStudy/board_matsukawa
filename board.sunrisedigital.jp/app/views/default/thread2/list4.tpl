{extends file='default/base.tpl'}
{block title append} スレッドリスト{/block}
{block main_contents}
<ul>
  {foreach $threadt_list as $threadt}
  <li>
    <div class="account">
        <a href="/entry/alist{$threadt->getId()}?thread_id={$threadt->getId()}">{$threadt->getTitle()}</a>
         
    </div>

  </li>
  {/foreach}
</ul>  
{/block}