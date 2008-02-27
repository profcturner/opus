{* Smarty *}

{include file="reports/input_header.tpl"}

      <tr>
        <td class="property">Extra Information</td>
        <td>
          {html_checkboxes name="extras" options=$extras separator="<br />" selected=$report_options.extras}
        </td>
      </tr>
      <tr>
        <td class="property">Assessment Group</td>
        <td>{html_options name="assessment_group" options=$assessmentgroups selected=$report_options.assessment_group}</td>
      </tr>
      <tr>
        <td class="property">Year Seeking Placement</td>
        <td>
          <input type="text" size="5" name="year" value="{$report_options.year}">
        </td>
      </tr>
      {include file="reports/format_selector.tpl"}
{include file="reports/input_footer.tpl"}