{* Smarty *}

{include file="form_start.tpl" form=$form}
<table align="center">

<tr>
  <th>Name</th>
  <td><input type="TEXT" class="data_entry_required" NAME="name" SIZE="30" VALUE="{$company.name|escape:"htmlall"}"></TD></TR>

<TR>
  <th>Address 1</th>
  <TD><INPUT TYPE="TEXT" class="data_entry_required" NAME="address1" SIZE="30" VALUE="{$company.address1|escape:"htmlall"}"></td>
</tr>

<TR>
  <th>Address 1</th>
  <TD><INPUT TYPE="TEXT" NAME="address2" SIZE="30" VALUE="{$company.address2|escape:"htmlall"}"></td>
</tr>

<TR>
  <th>Address 1</th>
  <TD><INPUT TYPE="TEXT" NAME="address3" SIZE="30" VALUE="{$company.address3|escape:"htmlall"}"></td>
</tr>

<tr>
  <th>Town / City</th>
  <td><input type="TEXT" name="town" class="data_entry_required" size="30" VALUE="{$company.town|escape:"htmlall"}"></td>
</tr>

<TR>
  <th><acronym title="The locality may be the city if it is large (e.g. Belfast), or the county etc.">Locality</acronym></th>
  <TD><INPUT TYPE="TEXT" class="data_entry_required" NAME="locality" SIZE="30" VALUE="{$company.locality|escape:"htmlall"}"></td>
</tr>

<TR><th>Country</th><TD>
  <INPUT TYPE="TEXT" class="data_entry_required" NAME="country" SIZE="30" VALUE="{$company.country|escape:"htmlall"}">
</TD></TR>

<TR><th>Post Code</th><TD>
  <INPUT TYPE="TEXT" NAME="postcode" SIZE="10" VALUE="{$company.postcode|escape:"htmlall"}">
</TD></TR> 

<TR><th>Web Address</th><TD>
  <INPUT TYPE="TEXT" NAME="www" SIZE="30" VALUE="{$company.www|escape:"htmlall"}">
{if $company.www}	
  <a href="http://{$company.www}" target="blank"><small>(visit link)</small></a>
{/if}
</TD></TR> 

<TR><th>Phone</th><TD>
  <INPUT TYPE="TEXT" NAME="voice" SIZE="30" VALUE="{$company.voice|escape:"htmlall"}">
</TD></TR> 

<TR><th>Fax</th><TD>
  <INPUT TYPE="TEXT" NAME="fax" SIZE="30" VALUE="{$company.fax|escape:"htmlall"}">
</TD></TR>

<tr>
  <th>Allocation (kb)</th>
  <td>

{if $session.user.type == 'root' || $session.user.type == 'admin'}
  <INPUT TYPE="TEXT" NAME="allocation" SIZE="30" VALUE="
{/if} {* admins only *}
{if $company.allocation}{$company.allocation}{else}default ( {$conf.prefs.allocation} ){/if}
{if $session.user.type == 'root' || $session.user.type == 'admin'}">{/if}
{if $company.allocation_used}Used {$company.allocation_used} kb{/if}
</TD></TR>

<tr>
  <th>Activities</th>
  <td>{html_checkboxes name="activities" options=$activities selected=$company_activities separator="<br />"}</td>
</tr>

<tr>
  <th colspan="2" align="center">Brief</th>
</tr>
<tr>
  <td colspan="2"><TEXTAREA class="data_entry_required" NAME="brief" ROWS="20" COLS="80" id="HAEditor">
{$company.brief|escape:"htmlall"}
</TEXTAREA></TD>
</tr>
  
<TR>
  <TD COLSPAN="2" ALIGN="CENTER">
  <INPUT TYPE="submit" NAME="button" VALUE="Submit">
  <INPUT TYPE="reset" VALUE="Reset"></TD>
</TR>


</TABLE>
</FORM>

{if $brief.output}
<hr>
<h3 align="center">Existing brief renders as follows</h3>
<p>{$brief.output}</p>
{/if}
{if $brief.warnings}
<h3 align="center" class="warning">Warnings were found parsing the XHTML</h3>
<p>XHTML errors are most often caused by pasting from programs like Word, which unfortunately
paste huge amounts of proprietary markup code. <strong>If the brief above appears as you
expect it, then you need take no other action, these errors will not be displayed to
students viewing the company.</strong> If the brief is not as you expect it, try using the
two buttons on the editor to "remove formatting" and clear "MSOffice tags". These are the
last buttons on the second row. If you still experience problems, use the 
<a href="{$conf.scripts.user.helpdir}">Help Directory</a> to find someone who can help.</p>
{$brief.warnings}
{/if}

