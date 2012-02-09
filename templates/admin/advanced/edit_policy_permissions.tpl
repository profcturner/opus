{* Smarty *}

{*<div id="action_area">*}
{*Actions 
  <span id="action_button"><a href="?section=advanced&function=manage_policies">cancel</a></span>*}
{*</div>
<br />*}
<div id="table_manage">
  <form method="post" action="">
    <input type="hidden" name="section" value="advanced" />
    <input type="hidden" name="function" value="edit_policy_permissions_do" />
    <input type="hidden" name="id" value="{$policy->id}" />

    <table>
    <tr>
      <td colspan="4" class="button"><input type="submit" class="submit" value="confirm" /></td>
    </tr>

      <tr>
        <td class="property">Students</td>
        <td>
          {html_checkboxes name="student" values=$student_possible separator="<br />" selected=$student_selected output=$student_output|capitalize}
        </td>
        <td class="property">Academic Staff</td>
        <td>
          {html_checkboxes name="staff" values=$staff_possible separator="<br />" selected=$staff_selected output=$staff_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Companies</td>
        <td>
          {html_checkboxes name="company" values=$company_possible separator="<br />" selected=$company_selected output=$company_output|capitalize}
        </td>
        <td class="property">Vacancies</td>
        <td>
          {html_checkboxes name="vacancy" values=$vacancy_possible separator="<br />" selected=$vacancy_selected output=$vacancy_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Company Contacts</td>
        <td>
          {html_checkboxes name="contact" values=$contact_possible separator="<br />" selected=$contact_selected output=$contact_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Channels</td>
        <td>
          {html_checkboxes name="channel" values=$channel_possible separator="<br />" selected=$channel_selected output=$channel_output|capitalize}
        </td>
        <td class="property">Help and Prompts</td>
        <td>
          {html_checkboxes name="help" values=$help_possible separator="<br />" selected=$help_selected output=$help_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Resources</td>
        <td>
          {html_checkboxes name="resource" values=$resource_possible separator="<br />" selected=$resource_selected output=$resource_output|capitalize}
        </td>
        <td class="property">Automail Templates</td>
        <td>
          {html_checkboxes name="automail" values=$automail_possible separator="<br />" selected=$automail_selected output=$automail_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Faculties</td>
        <td>
          {html_checkboxes name="faculty" values=$faculty_possible separator="<br />" selected=$faculty_selected output=$faculty_output|capitalize}
        </td>
        <td class="property">Schools</td>
        <td>
          {html_checkboxes name="school" values=$school_possible separator="<br />" selected=$school_selected output=$school_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Programmes</td>
        <td>
          {html_checkboxes name="programme" values=$programme_possible separator="<br />" selected=$programme_selected output=$programme_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">CV Groups</td>
        <td>
          {html_checkboxes name="cvgroup" values=$cvgroup_possible separator="<br />" selected=$cvgroup_selected output=$cvgroup_output|capitalize}
        </td>
        <td class="property">Assessment Groups</td>
        <td>
          {html_checkboxes name="assessmentgroup" values=$assessmentgroup_possible separator="<br />" selected=$assessmentgroup_selected output=$assessmentgroup_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Status</td>
        <td>
          {html_checkboxes name="status" values=$status_possible separator="<br />" selected=$status_selected output=$status_output|capitalize}
        </td>
        <td class="property">Import Data</td>
        <td>
          {html_checkboxes name="import" values=$import_possible separator="<br />" selected=$import_selected output=$import_output|capitalize}
        </td>
      </tr>
      <tr>
        <td class="property">Log Files</td>
        <td>
          {html_checkboxes name="log" values=$log_possible separator="<br />" selected=$log_selected output=$log_output|capitalize}
        </td>
      </tr>
      <tr>
        <td colspan="4" class="button"><input type="submit" class="submit" value="confirm" /></td>
     </tr>
    </table>
  </form>
</div>
