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
    <th class="action">CV</th>
    <th class="action">Letter</th>
    <th class="action">Edit</th>
  </tr>
  {section name=applications loop=$applications}
  <tr class="{cycle values="dark_row,light_row"}">
    <td><input type="checkbox" name="send[]" value={$applications[applications]->student_id} /></td>
    <td>{$applications[applications]->_student_real_name}</td>
    <td>{$applications[applications]->_student_programme}</td>
    <td>{$applications[applications]->created}{if $applications[applications]->modified}<br />Modified: {$applications[applications]->modified}{/if}</td>
    <td>{html_options name="status["|cat:$applications[applications]->student_id|cat:"]" values=$status_values output=$status_values selected=$applications[applications]->status}<input type="hidden" name="old_status[{$applications[applications]->student_id}]" value={$applications[applications]->status} /></td>
    <td class="action"><a href="">cv</a></td>
    <td class="action">{if $applications[applications]->letter}<a href="">letter</a>{else}letter{/if}</td>
    <td class="action"><a href="?section=directories&function=edit_student&id={$applications[applications]->student_id}">edit</a></td>
  </tr>
  {/section}
</table>
</div>