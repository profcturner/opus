{* Smarty *}

<table>
<tr>
  <th>Description</th>
  <td>{$resource->description|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Channel</th>
  <td>{$resource->_channel_id|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Downloads</th>
  <td>{$resource->dcounter}</td>
</tr>
{if $resource->author}
<tr>
  <th>Author</th>
  <td>{$resource->author|escape:"htmlall"}</td>
</tr>
{/if}
{if $resource->copyright}
<tr>
  <th>Copyright</th>
  <td>{$resource->copyright|escape:"htmlall"}</td>
</tr>
{/if}
</table>