<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    {foreach $headings as $heading}<th>{$heading}</th>{/foreach}
  </tr>
  {foreach $rows as $row}
  <tr class="{cycle values="dark_row,light_row"}">
    {foreach $headings as $heading}<td>$row[$heading]</td>
  </tr>
  {/foreach}
</table>
</div>
