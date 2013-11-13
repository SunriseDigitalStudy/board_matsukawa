{extends file='default/base.tpl'}
{block title append} スレッドリスト{/block}
{block main_contents}
<ul>
  {foreach $threadt_list as $threadt}
  <li>
    <div class="account">{$threadt->getTitle()}</div>
{*    <ul>
      {foreach $genere->getEntryList() as $entry}
      <li>
        <div>{$genre->getCreatedAt()}</div>
        <div>{$genre->getThread()->getTitle()}</div>
        <div>{$genre->getBody()}</div>
      </li>
      {/foreach}
    </ul>*}
  </li>
  {/foreach}
</ul>  
{/block}