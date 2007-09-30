{* Smarty *}

<div id="table_manage">
<table>
<tr>
  <td class="property">Description</td>
  <td>{$resource->description|escape:"htmlall"}</td>
</tr>
<tr>
  <td class="property">Channel</td>
  <td>{$resource->_channel_id|escape:"htmlall"}</td>
</tr>
<tr>
  <td class="property">Downloads</td>
  <td>{$resource->dcounter}</td>
</tr>
<tr>
  <td class="property">Author</td>
  <td>{$resource->author|escape:"htmlall"|default:"Unknown"}</td>
</tr>
<tr>
  <td class="property">Copyright</td>
  <td>{$resource->copyright|escape:"htmlall"|default:"Unknown"}</td>
</tr>
<tr>
  <td class="property">Initially Uploaded</td>
  <td>{$resource->created|date_format:""}</td>
</tr>
<tr>
  <td class="property">Last modified</td>
  <td>{$resource->modified|date_format:""}</td>
</tr>
<tr>
  <td class="property">Last downloaded</td>
  <td>{$resource->downloaded|default:"never"}</td>
</tr>
</table>
</div>