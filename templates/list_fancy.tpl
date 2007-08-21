<div id="table_list">
{eval assign=object_num var=$objects|@count}
{if $object_num > #TABLE_ROW_MAX#}
{math assign=pages equation="ceil((x/y)+1)" x=$object_num y=#TABLE_ROW_MAX#}
{math assign=start_obj equation="(p-1)*n" n=#TABLE_ROW_MAX# p=$page|default:1}
pages
{section name="myLoop" start=1 loop=$pages}
{if $page==$smarty.section.myLoop.index}<strong>{$smarty.section.myLoop.index}</strong>&nbsp;{else}<a href="{$SCRIPT_NAME}?function={$smarty.request.function}&page={$smarty.section.myLoop.index}">{$smarty.section.myLoop.index}</a>&nbsp;{/if}
{/section}
{/if}
{section loop=$objects name=object start=$start_obj max=#TABLE_ROW_MAX#}
  <div class="{cycle values="dark_row,light_row"} fancy_table">
{foreach from=$headings key=key item=def}
    {if $def.header == true}
      <div class="{$def.listclass}">{eval assign=fn var=$key}
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
      {/if}</div>
    {/if}
{/foreach}

{section loop=$actions name=action}
    <div class="action">
      <a href="{#APPLICATION_URL#}/{#CONTROLLER_NAME#}?function={$actions[action][1]}&id={$objects[object]->id}">{$actions[action][0]}</a>&nbsp;
    </div>
{/section}
</div>
{/section}
</div>
