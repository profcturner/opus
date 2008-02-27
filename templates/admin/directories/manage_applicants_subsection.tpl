{* Smarty *}

<h3>{$subsection_title}</h3>
<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Send</th>
    <th>Applicant Name</th>
    <th>Programme</th>
    <th>Application Date</th>
    <th>Status</th>
    {if !$no_view}<th class="action">CV</th>
    <th class="action">Letter</th>{/if}
    <th class="action">Help</th>
    {if !$no_edit}<th class="action">Edit</th>{/if}
  </tr>
  {section name=applications loop=$applications}
  <tr class="{cycle values="dark_row,light_row"}">
    <td>{if !$no_view}<input type="checkbox" name="send[]" value={$applications[applications]->student_id} />{/if}</td>
    <td><a href="mailto:{$applications[applications]->_student_email}">{$applications[applications]->_student_real_name}</a></td>
    <td>{$applications[applications]->_student_programme}</td>
    <td>{$applications[applications]->created}{if $applications[applications]->modified}<br />Modified: {$applications[applications]->modified}{/if}</td>
    <td>{html_options name="status["|cat:$applications[applications]->student_id|cat:"]" values=$status_values output=$status_values selected=$applications[applications]->status}<input type="hidden" name="old_status[{$applications[applications]->student_id}]" value="{$applications[applications]->status}" /></td>
    {if !$no_view}<td class="action"><a href="?section=directories&function=view_cv_by_application&application_id={$applications[applications]->id}">cv</a></td>
    <td class="action">{if $applications[applications]->cover}<a href="?section=directories&function=view_cover_by_application&application_id={$applications[applications]->id}">letter</a>{else}{/if}</td>{/if}
    <td class="action"><a href="?section=information&function=help_directory&student_id={$applications[applications]->_student_table_id}">help</a></td>
    {if !$no_edit}<td class="action"><a href="?section=directories&function=edit_student&id={$applications[applications]->_student_table_id}">edit</a></td>{/if}
  </tr>
  {/section}
</table>
</div>