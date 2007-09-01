<div id="table_list">
{eval assign=object_num var=$objects|@count}
{if $object_num > #TABLE_ROW_MAX#}
{math assign=pages equation="ceil((x/y)+1)" x=$object_num y=#TABLE_ROW_MAX#}
{math assign=start_obj equation="(p-1)*n" n=#TABLE_ROW_MAX# p=$page|default:1}
pages
{section name="myLoop" start=1 loop=$pages}
{if $page==$smarty.section.myLoop.index}<strong>{$smarty.section.myLoop.index}</strong>&nbsp;{else}<a href="{$SCRIPT_NAME}?section={$section}&function={$smarty.request.function}&page={$smarty.section.myLoop.index}">{$smarty.section.myLoop.index}</a>&nbsp;{/if}
{/section}
{/if}
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
{foreach from=$headings key=key item=def}
    {if $def.header == true}<th>{if $def.title}{$def.title}{else}{$key|replace:"_":" "|capitalize}{/if}</th>{/if}
{/foreach}
{section loop=$actions name=action}
    <th class="action">{$actions[action][0]|capitalize}</th>
{/section}
  </tr>
{section loop=$objects name=object start=$start_obj max=#TABLE_ROW_MAX#}
  <tr class="{cycle values="dark_row,light_row"}">
{foreach from=$headings key=key item=def}
    {if $def.header == true}
      <td>{eval assign=fn var=$key}
      {if $def.type == "email"}
      <a href="mailto:{$objects[object]->$fn}">{$objects[object]->$fn}</a>
      {elseif $def.type == "file"}
        <a href="">{$objects[object]->$fn}</a>
      {elseif $def.type == "lookup"}
        {eval assign=fn2 var="_$key"}{if $objects[object]->$fn2}{$objects[object]->$fn2}{else}{$objects[object]->$fn}{/if}
      {elseif $def.type == "date"}
        {$objects[object]->$fn|date_format}
         {elseif $def.type == "link"}
            <a href="{#APPLICATION_URL#}/{#CONTROLLER_NAME#}?function={$def.url}&id={$objects[object]->id}">{$objects[object]->$fn}</a>
      {elseif $def.type == "currency"}
        &pound;{$objects[object]->$fn|string_format:"%.2f"}
      {else}
        {$objects[object]->$fn|nl2br}
      {/if}</td>
    {/if}
{/foreach}

{section loop=$actions name=action}
    <td class="action">
      <a href="?section={$section}&function={$actions[action][1]}&id={$objects[object]->id}">{$actions[action][0]}</a>&nbsp;
    </td>
{/section}
  </tr>
{/section}
</table>
</div>