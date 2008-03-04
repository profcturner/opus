{* Smarty *}
<div id="table_manage">
  <table>
    <tr>
      <td class="property">Author</td>
      <td>{$note->_author_id|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Summary</td>
      <td>{$note->summary|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Date</td>
      <td>{$note->date}</td>
    </tr>
    <tr>
      <td class="property">Authorization</td>
      <td>{$note->auth|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Contents</td>
      <td>{$note->display()}</td>
    </tr>
  </table>
</div>

{#other_links#}

<div id="table_list">
{section name=note_links loop=$note_links}
{if $smarty.section.note_links.first}
<table>
<tr>
  <th>Type</th>
  <th>Name</th>
  <th>Primary</th>
</tr>
{/if}
<tr>
  <td>{$note_links[note_links]->link_type}</td>
  <td>{$note_links[note_links]->_human_link_name|escape:"htmlall"}</td>
  <td>{$note_links[note_links]->main}</td>
</tr>
{if $smarty.section.note_links.last}
</table>
{/if}
{/section}
</div>