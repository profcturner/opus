{* Smarty *}

<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Author</th>
    <th>Summary</th>
    <th>Time</th>
    <th>Primary</th>
    <th class="action">View</th>
  </tr>
  {section name=notes loop=$notes}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$notes[notes].author_name|escape:"htmlall"}</td>
    <td>{$notes[notes].summary|escape:"htmlall"}</td>
    <td>{$notes[notes].date|escape:"htmlall"}</td>
    <td>{$notes[notes].main|default:#unknown#|escape:"htmlall"}</td>
    <td class="action"><a href="?section={$section|default:"directories"}&function=view_note&id={$notes[notes].id}">view</a></td>
  </tr>
  {sectionelse}
  {#none_found#}
  {/section}
</table>
</div>
