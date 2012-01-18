<div id="table_manage">
<form method="POST" ENCTYPE="multipart/form-data" action="?section=main&function=edit_preferences_do" name="mainform">
<table class="preferences" cellpadding="0" cellspacing="0" border="0">

	<tr><th colspan="2" style="padding: 1em 0em; float:left;">Style</th></tr>
	<tr>
		<td class="property">System Theme</td>
		<td>
			<select name="system_theme" style="font-size:12px;">
				<option value="blue" {if $system_theme == 'blue'}selected{/if}>blue</option>
				<option value="red" {if $system_theme == 'red'}selected{/if}>red</option>
				<option value="green" {if $system_theme == 'green'}selected{/if}>green</option>
				<option value="gray" {if $system_theme == 'gray'}selected{/if}>gray</option>
			</select>
		</td>
	</tr>

	<tr>
    <td colspan="2" class="button"><input type="submit" onSubmit="close_window()" class="submit" value="save" style="font-size:12px;"/><input type="hidden" name="section" value="main" onSubmit="close_window()" /><input type="hidden" name="function" value="edit_preferences_do" onSubmit="close_window()"/></td>
  </tr>
</table>
<input type="hidden" name="referrer" value="{$referrer}" onSubmit="close_window()" />
</form>
</div>
