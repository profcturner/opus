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
<tr>
  <th>Author</th>
  <td>{$resource->author|escape:"htmlall"|default:"Unknown"}</td>
</tr>
<tr>
  <th>Copyright</th>
  <td>{$resource->copyright|escape:"htmlall"|default:"Unknown"}</td>
</tr>
<tr>
  <th>Initially Uploaded</th>
  <td>{$resource->created}</td>
</tr>
<tr>
  <th>Last modified</th>
  <td>{$resource->modified|default:"unmodified"}</td>
</tr>
<tr>
  <th>Last downloaded</th>
  <td>{$resource->downloaded|default:"never"}</td>
</tr>
</table>