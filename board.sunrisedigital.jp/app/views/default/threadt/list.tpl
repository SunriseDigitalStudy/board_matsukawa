{extends file='default/base.tpl'}
{block title append} アカウントリスト{/block}
{block main_contents}
<ul>
  {foreach $treadt_list as $treadt}
  <li>
    <div class="account">{$treadt->getName()}</div>
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