{* Smarty *}
{* Presents the form to edit an existing resource *}

<h2>Editing Resource</h2>
<h3>Editing Resource {$resource_info.lookup|escape:"htmlall"}, in language {$resource_info.language_name|escape:"htmlall"}
{if $resource_info.channel_name} and channel {$resource_info.channel_name|escape:"htmlall"}{/if}</h3> 

<form enctype="MULTIPART/FORM-DATA" action="{$conf.scripts.admin.resource_dir}?mode=ResourceUpdate&resource_id={$resource_info.resource_id}" method="post">
<table class="opus_resource_edit_table">
<tr>
  <th>Filename to upload</th>
  <td><input name="userfile" type="file"></td>
</tr>
<tr>
  <th>Filename shown to users</th>
  <td><input name="filename" value="{$resource_info.filename}" size="40" class="data_entry_required">
</tr>
<tr>
  <th>Language</th>
  <td><select name="language_id">{html_options options=$languages selected=$resource_info.language_id}</select></td>
</tr>
<tr>
  <th>Channel</th>
  <td><select name="channel_id">{html_options options=$channels selected=$resource_info.channel_id}</select></td>
</tr>
<tr>
  <th>Lookup</th>
  <td><input name="lookup" size="30" type="text" value="{$resource_info.lookup}" class="data_entry_required">
</tr>
<tr>
  <th>Description</th>
  <td><input name="description" size="40" type="text" value="{$resource_info.description|escape:"htmlall"}" class="data_entry_required">
</tr>
<tr>
  <th>Author</th>
  <td><input name="author" size="40" type="text" value="{$resource_info.author|escape:"htmlall"}">
</tr>
<tr>
  <th>Copyright</th>
  <td><input name="copyright" size="40" type="text" value="{$resource_info.copyright|escape:"htmlall"}">
</tr>
<tr>
  <th>Authorisation</th>
  <td><input name="auth" size="40" type="text" value="{$resource_info.auth|escape:"htmlall"}">
</tr>
</table>
<input type="submit" value="Update Resource">

{* Add a line like this :<input type="hidden" name="MAX_FILE_SIZE" value="1000"> *}

<p>Only fill the filename to upload field if you actually wish to update the file itself.</p>

<h3>Additional Information</h3>
<table>
<tr>
  <th>Uploaded by</th>
  <td>{$resource_info.uploader_name|escape:"htmlall"}</td>
</tr>
<tr>
  <th>Uploaded on</th>
  <td>{$resource_info.created}</td>
</tr>
<tr>
  <th>Modified on</th>
  <td>{$resource_info.modified}</td>
</tr>
<tr>
  <th>Download counter</th>
  <td>{$resource_info.dcounter}</td>
</tr>
<tr>
  <th>Last download</th>
  <td>{$resource_info.downloaded}</td>
</tr>
<tr>
  <th>Mime Type</th>
  <td>{$resource_info.mime_type}</td>
</tr>
<tr>
  <th>File Size</th>
  <td>{$resource_info.file_size}</td>
</tr>
</table>
