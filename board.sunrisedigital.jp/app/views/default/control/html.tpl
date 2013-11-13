{strip}
{if $i18n}
  {include "sdx/i18n/{$i18n->getCode()}/scaffold/html.tpl" scope=parent}
{else}
  {include 'sdx/i18n/ja/scaffold/html.tpl' scope=parent}
{/if}
{$is_separated = !($enable_edit && $enable_list)}
<div id="scaffold-wrapper">
  {if $include.header}
    <div id="inner-header">{include file=$include.header}</div>
  {/if}
  {if $exception}
    <div class="alert alert-danger">
      <strong>{$i18n_scaffold_exception}</strong><br>
      {$exception->getMessage()}
      <div class="text-right">
        <a href="{$origin_uri->toString()}" class="alert-link">{$i18n_scaffold_reload}</a>
      </div>
    </div>
  {/if}
  {if $navi && $enable_list}
    <a href="{$navi.uri}" class="btn btn-link">{$navi.label nofilter}</a>
  {/if}
  {if $enable_edit}{include 'sdx/include/scaffold/edit_form.tpl'}{/if}
  {if $enable_list}{include 'sdx/include/scaffold/list.tpl'}{/if}
  {if $include.footer}
    <div id="inner-footer">{include file=$include.footer page_name=$page_name}</div>
  {/if}
</div>
{/strip}