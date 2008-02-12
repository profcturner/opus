{* Smarty *}

{include file="reports/input_header.tpl"}

      <tr>
        <td class="property">Assessment Group</td>
        <td>{html_options name="assessment_group" options=$assessmentgroups selected=$report_options.assessment_group}</td>
      </tr>
{include file="reports/input_footer.tpl"}