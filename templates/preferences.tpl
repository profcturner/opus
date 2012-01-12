<div id="table_manage">
<form method="POST" ENCTYPE="multipart/form-data" action="?section=main&function=edit_preferences_do" name="mainform">
<table class="preferences" cellpadding="0" cellspacing="0" border="0">
	<tr><th colspan="2" style="padding: 1em 0em; float:left;">Tools</th></tr>
	<tr>
		<td class="property">Resources Active</td>
		<td>
			<select name="resources_active" style="font-size:12px;">
				<option value="yes" {if $resources_active == 'yes'}selected{/if}>yes</option>
				<option value="no" {if $resources_active == 'no'}selected{/if}>no</option>
			</select>
		</td>
	</tr>
	<tr>
		<!--<td class="property">Bookmarks Active</td>
		<td>
			<select name="bookmarks_active" disabled>
				<option value="yes" {if $bookmarks_active == 'yes'}selected{/if}>yes</option>
				<option value="no" {if $bookmarks_active == 'no'}selected{/if}>no</option>
			</select>
		</td> -->
	</tr>
	<tr>
		<td class="property">Trails Active</td>
		<td>
			<select name="trails_active" style="font-size:12px;">
				<option value="yes" {if $trails_active == 'yes'}selected{/if}>yes</option>
				<option value="no" {if $trails_active == 'no'}selected{/if}>no</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="property">Schedule Active</td>
		<td>
			<select name="calendar_active" style="font-size:12px;">
				<option value="yes" {if $calendar_active == 'yes'}selected{/if}>yes</option>
				<option value="no" {if $calendar_active == 'no'}selected{/if}>no</option>
			</select>
		</td>
	</tr>
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
	{if $email_accounts|@count > 1}
	<tr><th colspan="2" style="padding: 1em 0em; float:left;">Email</th></tr>
	
	<tr>
		<td class="property">Preferred Email Account</td>
		<td>
			
			<select name="preferred_email_account" style="font-size:12px;">
			{html_options options=$email_accounts selected=$preferred_email_account}
			</select>	
		</td>
	</tr>
	{/if}
	<tr><th colspan="2" style="padding: 1em 0em; float:left;">Messaging</th></tr>
	<tr>
		<td class="property">Hide Read Messages</td>
		<td>
			<select name="hide_read_messages" style="font-size:12px;">
				<option value="no" {if $hide_read_messages == 'no'}selected{/if}>no</option>
				<option value="yes" {if $hide_read_messages == 'yes'}selected{/if}>yes</option>
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
