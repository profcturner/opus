{* Smarty *}

{include file="reports/input_header.tpl"}

{literal}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(checked)
{
  for (i = 0; i < document.input_stage_1.elements.length; i++) {
    if (document.input_stage_1.elements[i].name.indexOf('activities') >= 0) 
    {
      document.input_stage_1.elements[i].checked = checked;
    }
  }
}
// -->
</script>
{/literal}

      <tr>
        <td class="property">Activities</td>
        <td>
        <a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
        {html_checkboxes name="activities" options=$activity_types selected=$report_options.activities separator="<br />"}
        <a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
        </td>
      </tr>
      <tr>
        <td class="property">Start Year</td>
        <td>
          <input type="text" name="start_year" size="5" value="{$report_options.start_year}"/>
        </td>
      </tr>
      <tr>
        <td class="property">End Year</td>
        <td>
          <input type="text" name="end_year" size="5" value="{$report_options.end_year}"/>
        </td>
      </tr>


      {include file="reports/format_selector.tpl"}
{include file="reports/input_footer.tpl"}