{* Smarty *}


{if $form.hidden.mode == "VacancyUpdate"}
<H2 ALIGN="CENTER">Editing Vacancy "{$vacancy.description|escape:"htmlall"}"</H2>
{else}
<H2 ALIGN="CENTER">Adding New Vacancy</H2>
{if $clone}
<p align="center"><strong>This is an unsaved clone of an existing vacancy.</strong><br /> Of course, you will almost
certainly want to edit details like the description and / or dates involved before
saving.</p>
{/if} {* clone *}
{/if}
<H3 ALIGN="CENTER">{$vacancy.company_name|escape:"htmlall"}</H3>

{include file="form_start.tpl" form=$form}
<TABLE ALIGN="CENTER">
  <TR>
    <th><acronym title="A job description, be as descriptive as possible">Description</acronym></th>
    <TD><INPUT TYPE="TEXT" class="data_entry_required" NAME="description" SIZE="30" VALUE="{$vacancy.description}"></TD>
  <TR>
  <th>Activities<BR><small>(Hold CTRL while clicking<BR> to select several entries)</small></th>
    <TD><select multiple name="activities[]">
{html_options options=$activities selected=$selected_activities}
</select>
    </TD>
  </TR>
  </TR>
  <TR>
    <th>Address 1</th>
    <TD><INPUT TYPE="TEXT" NAME="address1" SIZE="30" VALUE="{$vacancy.address1}"></TD>
  </TR>
  <TR>
    <th>Address 2</th>
    <TD><INPUT TYPE="TEXT" NAME="address2" SIZE="30" VALUE="{$vacancy.address2}"></TD>
  </TR>
  <TR>
    <th>Address 3</th>
    <TD><INPUT TYPE="TEXT" NAME="address3" SIZE="30" VALUE="{$vacancy.address3}"></TD>
  </TR>
  <TR>
    <th>Town / City</th>
    <TD><INPUT TYPE="TEXT" NAME="town" SIZE="30" VALUE="{$vacancy.town}"></TD>
  </TR>
  <TR>
    <th>Locality</th>
    <TD><INPUT TYPE="TEXT" NAME="locality" SIZE="30" VALUE="{$vacancy.locality}"></TD>
  </TR>
  <TR>
    <th>Country</th>
    <TD><INPUT TYPE="TEXT" NAME="country" SIZE="30" VALUE="{$vacancy.country}"></TD>
  </TR>
  <TR>
    <th>Postcode</th>
    <TD><INPUT TYPE="TEXT" NAME="postcode" SIZE="30" VALUE="{$vacancy.postcode}"></TD>
  </TR>
  <TR>
    <th>Web Address</th>
    <TD><INPUT TYPE="TEXT" NAME="www" SIZE="30" VALUE="{$vacancy.www}"></TD>
  </TR>
  <TR>
    <th>Salary</th>
    <TD><INPUT TYPE="TEXT" NAME="salary" SIZE="20" VALUE="{$vacancy.salary}"></TD>
  </TR>
  <TR>
    <th><acronym title="May be approximate, but needed">Job Start Date</acronym></th>
    <TD>
      <INPUT class="data_entry_required" TYPE="TEXT" NAME="jobstart" SIZE="11" VALUE="{$vacancy.jobstart}">
<acronym title="ISO date format is YYYY-MM-DD">ISO date format</acronym>
{*      {include file="calendar_popup.tpl" date_input="jobstart"} *}
    </TD>
  </TR>
  <TR>
    <th>Job End Date</th>
    <TD>
      <INPUT TYPE="TEXT" NAME="jobend" SIZE="11" VALUE="{$vacancy.jobend}">
<acronym title="ISO date format is YYYY-MM-DD">ISO date format</acronym>
{*      {include file="calendar_popup.tpl" date_input="jobend"} *}

    </TD>
  </TR>
  <TR>
    <th>Application Close Date/Time</th>
    <TD>
      <INPUT TYPE="TEXT" class="calendar" NAME="closedate" SIZE="11" VALUE="{$vacancy.closedate}">
	<input type="text" name="closedate_time" size="9" value="{$vacancy.closedate_time}">
<acronym title="ISO date format is YYYY-MM-DD">ISO date format</acronym>
{*      {include file="calendar_popup.tpl" date_input="closedate"} *}
    </TD>
  </TR>
  <TR>
    <th>Contact</th>
    <TD><SELECT NAME="contact_id">
{html_options values=$form.data.contacts.ids output=$form.data.contacts.names selected=$vacancy.contact_id}</SELECT></TD>
  </TR>
  <TR>
    <th>Application Status</th>
    <TD><SELECT NAME="status">
{html_options values=$form.data.status output=$form.data.status selected=$vacancy.status}</SELECT></TD>
  </TR>
  <TR>
    <th COLSPAN="2" ALIGN="CENTER">Brief</th>
  </TR>
  <TR>
    <TD COLSPAN="2"><TEXTAREA class="editor"  id="HAEditor" NAME="brief" style="width:100%" ROWS="20" COLS="80">{$vacancy.brief|escape:"htmlall"}</TEXTAREA></TD>

  </TR>
  <TR>
    <TD COLSPAN="2" ALIGN="CENTER">
      <INPUT TYPE="submit" NAME="button" VALUE="Submit">
      <INPUT TYPE="reset" VALUE="Reset">
    </TD>
  </TR>
</TABLE>
</FORM>

{if $brief.output}
<hr>
<h3 align="center">Existing brief renders as follows</h3>
{$brief.output}
{/if}
{if $brief.warnings}
<h3 align="center" class="warning">Warnings were found parsing the XHTML</h3>
<p>XHTML errors are most often caused by pasting from programs like Word, which unfortunately
paste huge amounts of proprietary markup code. <strong>If the brief above appears as you
expect it, then you need take no other action, these errors will not be displayed to
students viewing the vacancy.</strong> If the brief is not as you expect it, try using the
two buttons on the editor to "remove formatting" and clear "MSOffice tags". These are the
last buttons on the second row. If you still experience problems, use the 
<a href="{$conf.scripts.user.helpdir}">Help Directory</a> to find someone who can help.</p>
{$brief.warnings}
{/if}

