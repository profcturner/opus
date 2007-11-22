{* Smarty *}

<p align="center">
{if $session.user.id}
<table bgcolor="#ffecc9" class="table_with_border" width="250px" align="center">
<tr><th colspan="2">You are already logged in.</th></tr>
<tr><td colspan="2">Username: {$session.user.username|escape:"htmlall"}</td></tr>
<tr>
<td align="left"><a href="{$conf.scripts.user.index}?mode=Logout">Logout</a></td>
<td align="right" class="align-right"><a href="{$conf.scripts.user.login}">Go To Home Page</a></td>
</tr>
</table>
{else}
<form method="post" action="{$conf.scripts.user.index}">
<input type="hidden" name="mode" value="Login">
<table bgcolor="#ffecc9" class="table_with_border" width="250px" align="center"><tr><th colspan="2" align="left">Login</th>
<tr><th>Username</th><td><input name="username" type="text" class="data_entry_required" size="20"></td></tr>
<tr><th>Password</th><td><input name="password" type="password" class="data_entry_required" size="20"></td></tr>
{if $login_error}
<tr><td colspan="2" class="warning">Error - username or password incorrect</td></tr>
{/if}
<tr><td colspan="2"><input type="submit" value="login" class="button"></td></tr>
</table>
</form>
{/if}
</p>

