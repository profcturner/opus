
{if $print_version != "yes" }
<div id="outerWrapper">  <!-- outerwrapper starts -->
<div id="header">

{include file="sub_header.tpl"}
{include file="main_header.tpl"}



{* Need to tweak header size for double menus *}
</div> <!-- header -->
<div id="orientation"> <!-- orientation starts -->
  <div id="username"> <!-- username starts -->
 {$session.user.real_name}
  </div> <!-- username ends -->
    <div id="crumbtrail"> <!-- crumbtrail starts -->
      <h3><span class="here">{$page_title}</span></h3>
    </div> <!-- crumbtrail ends -->
</div> <!-- orientation ends -->

{* Another experiment, stack another menu if a student is picked up *}

{if $student_navigation}
{include file="main_header.tpl" navigation=$student_navigation section="myplacement"}
{/if}


<div id="content"> <!-- content starts -->
  <div id="container"> <!-- container starts -->
    <div id="main"> <!-- main content area start -->
    {if $page->preamble} 
     <div id="preamble"> <!-- preamble start -->
     <p>{$page->preamble|escape:"htmlall"}</p>
     </div> <!-- preamble ends -->
    {/if}

<table class="table_no_border">
  <tr>
    <td class="main_functional_area">

{/if} {* !$print_version *}
