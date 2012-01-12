<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1;">
<title>{#application_title#} {#version_text#} {#major_version#}.{#minor_version#}</title>
<link href="{$config.opus.url}/css/login.css" rel="stylesheet" type="text/css" />
<link href="{$config.opus.url}/css/splashcss.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../../opus/javascript/jquery.js"/></script>
	<script type="text/javascript" src="../../opus/javascript/jquery_login.js"></script>

<body>
<div id="splashcontainer">

<p>  &nbsp;
 		<img src="images/{$config.opus.logo}" alt="Institution Logo" />
  
  		<img src="images/{$config.opus.tagline}" align="right" alt="Application Logo" />
</p>

  <div id="slideshow">
  
  <img src="{$config.opus.url}/images/mainslide2.png" alt="Application Overview Image" width="950" height="270" />				
  
  </div>
  
  <div class="loginbox">
  
  	
  <table width="100%">
  <tr>
  <td>
  	<img src="{$config.opus.url}/images/login.jpg" width="285" height="34" style="display:inline; float:left; padding-right:10px; padding-left:6px;" alt="Login Arrow Image"/>
  </td>
  <td style="float:right;">
  	  <form method=POST action="{if $config[opus][cleanurls]}{#application_url#}home/home/{else}{#application_url#}?section=welcome&function=home{/if}">
		<input type="hidden" name="section" value="{$referrer_section}" />
		<input type="hidden" name="function" value="{$referrer_function}" />
		<input type="hidden" name="id" value="{$referrer_id}" />

		<table>
	 	<tr>
		  <td><div class="groups">{#username_text#}</div></td>
		  <td><input type="text" name="username" class="input" size="20"/></td>
		  <td><div class="groups">{#password_text#}</div></td>
		  <td><input type="password" name="password" class="input" size="20"/></td>
	  	  <td><input type="image" alt="Submit" align="left" src="{$config.opus.url}/images/loginbutton.png" value="{#login_phrase#}"/></td>
	  	</tr>
		</table><span class="important">{$error}</span>

      </form>
    </td>
    </tr>
    </table>
  </div>
  
  <div class="splashbox">
  
      <!--<h3><img src="{$config.opus.url}/images/guest_login.jpg" width="285" height="34" alt="" /></h3>-->
      
      <img src="{$config.opus.url}/images/employerwe.png" alt="Employer Image"/>
      
      <br/><br/>
		<div class='splashtext'>{#login_instructions_guest#}</div>
	</div>
	
	<div class="splashbox">
  
      <!--<h3><img src="{$config.opus.url}/images/graduate_login.jpg" width="285" height="34" alt=""/></h3>-->
      
      <img src="{$config.opus.url}/images/graduates.png" alt="Graduates Image" />	
      
      <br/><br/>
         
		<div class='splashtext'>{#login_instructions_graduates#}</div>
	</div>
  
 	<div class="splashbox">
  
      <!--<h3><img src="{$config.opus.url}/images/staff_student_login.jpg" width="285" height="34" alt=""/></h3>-->
      
      <img src="{$config.opus.url}/images/doforyou.jpg" alt="Students Image" />	
      
      <br/><br/>
      
		<div class='splashtext'>{#login_instructions_staff_students#}</div>
		
	</div>
	
  {if $failed_login}
    <div id="warning">{#failed_login#}</div><br />
  {/if}
 
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</body>
</html>
       
       
