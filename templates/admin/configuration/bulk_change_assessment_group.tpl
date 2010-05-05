{* Smarty *}

{literal}
<script language="JavaScript" type="text/javascript">
<!--

function toggleByDiv(divid, checked)
{
  var inputfields = document.getElementById(divid).getElementsByTagName('input');
  for (var i = 0; i < inputfields.length; i++)
  {
    if (inputfields[i].type.toUpperCase()=='CHECKBOX')
    {
      inputfields[i].checked = checked;
    }
  }
}

// -->
</script>
{/literal}


<h3>{#advanced_search#}</h3>
<div id="table_manage">


<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="section" value="configuration" />
    <input type="hidden" name="function" value="bulk_change_assessment_group_do" />

    <table>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="update" /></td>
      </tr>
      <tr>
        <td class="property">Assessment Group</td>
        <td>
          {html_options name="new_group_id" options=$assessmentgroups}
        </td>
      </tr>
      <tr>
        <td class="property">for placement starting in year</td>
        <td><input type="text" size="4" value ="{$from_year}" name="from_year"></td>
      </tr>
      <tr>
        <td class="property">Programmes</td>
        <td>
      
				<div id="school_{$school_id}" name="school_{$school_id}">
				<small>(<a href="" onclick="toggleByDiv('school_{$school.id}', true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleByDiv('school_{$school.id}', false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a>)</small><br />
              {html_checkboxes name="programme_ids[]" options=$programmes selected=$form_options.programmes separator="<br />"}
				<small>(<a href="" onclick="toggleByDiv('school_{$school.id}', true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleByDiv('school_{$school.id}', false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a>)</small><br />              
        </div>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="update" /></td>
      </tr>      
    </table>
  </form>
</div>