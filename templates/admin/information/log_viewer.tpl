{* Smarty *}

<form method="post" action="">
<input type="hidden" name="section" value="information">
<input type="hidden" name="function" value="view_logs">
Logfile
<select name="logfile">
{html_options output=$available_logs values=$available_logs selected=$selected_log}
</select>

Search
<input name="search" type="text" size="20" value="{$search}">

Lines
<input name="lines" type="text" size="5" value="{$lines}">

<input type="submit" value="Show">
</form>

<div id="table_list">
{foreach from=$log_lines item=line name=lines}
{if $smarty.foreach.lines.first}
<table class="table_list">
{/if}
<tr class="{cycle values="light_row,dark_row"}">
  <td>{$line}</td>
</tr>
{if $smarty.foreach.lines.last}
</table>
{/if}
{foreachelse}
<p>No log lines match your criteria.</p>
{/foreach}
</div>