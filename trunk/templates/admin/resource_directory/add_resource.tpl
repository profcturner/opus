{* Smarty *}
{* Displays a form for adding a new resource *}

<h3>Add New Resource</h3>

<form enctype="MULTIPART/FORM-DATA" action="{$conf.scripts.admin.resourcedir}?mode=ResourceInsert" method="post">
{* What is this? :<input type="hidden" name="status" value="{$status}"> *}
{if $company_id}
<input type="hidden" name="company_id" value="{$company_id}">
<input type="hidden" name="vacancy_id" value="{$vacancy_id}">
{/if} {* $company_id *}
<table>
  <tr>
    <th>Filename to upload</th>
    <td><input name="userfile" type="file"></td>
  </tr>
  <tr>
    <th>Filename shown to users</th>
    <td><input name="downloadname" size="40" type="text"></td>
  </tr>
  <tr>
    <th>Language</th>
    <td><select name="lang">{html_options options=$languages}</select></td>
  </tr>
  <tr>
    <th>Channels</th>
    <td><select name="channel_id">{html_options options=$channels}</select></td>
  </tr>
{if !$company_id}
  <tr>
    <th>Lookup</th>
    <td><input type="text" name="lookup" size="30"></td>
  </tr>
{/if} {* not company_id *}
  <tr>
    <th>Description</th>
    <td><input type="text" name="description" size="30"></td>
  </tr>
  <tr>
    <th>Author</th>
    <td><input type="text" name="author" size="30"></td>
  </tr>
  <tr>
    <th>Copyright</th>
    <td><input type="text" name="copyright" size="30"></td>
  </tr>
{if $company_id}
<input type="text" name="auth" value="all">
{else}
  <tr>
    <th>Authorisation</th>
    <td><input type="text" name="auth" size="30"></td>
  </tr>
{/if} {* company_id *}
</table>
<input type="submit" value="Add Resource">
