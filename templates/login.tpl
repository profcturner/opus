<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{#application_title#} {#version_text#} {#major_version#}.{#minor_version#}</title>
<link href="{$config.opus.url}/css/login.css" rel="stylesheet" type="text/css" />
  </head>

<body>

<table width="800" height="600"  border="0" align="center" cellpadding="0" cellspacing="0">
 
 <tr align="center" valign="middle">

  <!-- Border Top -->
  <td width="20" height="17" align="right" valign="bottom" background="{$config.opus.url}/images/corner1.jpg"></td>
  <td height="17" background="{$config.opus.url}/images/top.jpg"><div align="center"></div></td>  
  <td width="20" height="17" align="left" valign="bottom" background="{$config.opus.url}/images/corner2.jpg"></td>
 </tr>

 
 <tr align="center" valign="middle">
  
 
  <!-- Border Left -->
  <td width="20" align="center" valign="middle" background="{$config.opus.url}/images/left.jpg"><div align="center"></div></td>
  <td align="center" valign="middle">
   
     
  <!-- Top table (logos) -->
  <br />
<table width="700" height="90"  border="0" align="center" cellspacing="0">
 <tr valign="top">
  <td><div align="left"><img src="{#institution_logo#}"></div></td>
  <td width="350"><div align="right"><img src="{$config.opus.url}/images/{#application_logo#}"></div></td>
 </tr>
</table><br>
     
   
  <!-- Main Body table (text area's) -->
<table width="700"  border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
  
 
  <!-- Text area (left) -->
<td width="325" valign="top" class="text"><div align="left"><span class="titles"><br>
    {#application_title#}<small></small></span><br><br>
    {#application_description#}<br><br>
    </td>
  
    
  <!-- Text area middle (put nothing inside here) -->
  <td width="75">&nbsp;</td>
  <td width="300" valign="top"><table width="300"  border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td width="20" height="20" align="left" valign="top" bgcolor="#DAF1FC"><img src="{$config.opus.url}/images/login1.jpg" width="20" height="20" align="top" /></td>
  <td width="260" valign="top" bgcolor="#DAF1FC">&nbsp;</td>
  <td width="20" height="20" align="right" valign="top" bgcolor="#DAF1FC"><div align="right"><img src="{$config.opus.url}/images/login2.jpg" width="20" height="20" align="top" /></div></td>
 </tr>
        
 <tr bgcolor="#DAF1FC" class="login_bg">
  <td height="106" valign="top">&nbsp;</td>
  <td valign="top">
    <form method=POST action="{if $config[opus][cleanurls]}{#application_url#}home/home/{else}{#application_url#}?section=home&function=home{/if}">
    <input type="hidden" name="referrer_function" value="{$referrer_function}" />
<table width="300" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td width="90"><div class="groups">{#username_text#}</div></td>
  <td><input type="text" name="username" class="input" size="20"/></td>
 </tr>
 
 <tr>
  <td width="90"><div class="groups">{#password_text#}</div></td>
  <td><input type="password" name="password" class="input" size="20"/></td>
  </tr>
  
</table><br/>

  {if $failed_login}
    <div id="warning">{#failed_login#}</div><br />
  {/if}
                      
    <div class="groups"><input class="submit" type="submit" name="Submit" value="{#login_phrase#}" /></div>
    <br /><span class='text'>{#link_text_to_external_password_reset#}</span>
    <br />
    {if !$config[opus][disable_selfservice_password_reset]}
      <a href="{$config.opus.url}?function=request_recover_password">{#link_text_to_internal_password_reset#}</a>
    {/if}

    {if $opus_closed}<br /><h2>{#opus_closed#}</h2>{/if}
    <br><span class="important">{$error}</span>
      
    </form></td>

  <td valign="top">&nbsp;</td>
 </tr>
              
 <tr bgcolor="#DAF1FC">
  <td height="21" colspan="3"><hr align="center" width="100%" noshade="noshade" color="#FFFFFF"></td>
 </tr>
 
 <tr bgcolor="#DAF1FC">
  <td valign="top">&nbsp;</td>
  <td valign="top"><div class='text'>{#login_instructions#}</div></td>
  <td valign="top">&nbsp;</td>
 </tr>

 <tr>
  <td width="20" height="20" align="left" valign="bottom" bgcolor="#DAF1FC"><img src="{$config.opus.url}/images/login4.jpg" width="20" height="20" align="absbottom" /></td>
  <td width="260" valign="top" bgcolor="#DAF1FC">&nbsp;</td>
  <td width="20" height="20" align="right" valign="bottom" bgcolor="#DAF1FC"><img src="{$config.opus.url}/images/login3.jpg" width="20" height="20" align="absbottom" /></td>
 </tr>
</table>
  </td>
 </tr>
</table>
</td>
  <td width="20" align="center" valign="middle" background="{$config.opus.url}/images/right.jpg"><div align="center"></div></td>
  </tr>
 <tr align="center" valign="middle">
  <td width="20" height="17" align="right" valign="bottom" background="{$config.opus.url}/images/corner4.jpg"></td>
  <td height="17" background="{$config.opus.url}/images/bottom.jpg"><div align="center"></div></td>  
  <td width="20" height="17" align="left" valign="bottom" background="{$config.opus.url}/images/corner3.jpg"></td>
 </tr>
</table> 
</html>
