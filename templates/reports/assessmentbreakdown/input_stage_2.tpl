{* Smarty *}

{include file="reports/input_header.tpl"}

      <tr>
        <td class="property">Assessment</td>
        <td>{html_options name="assessment_regime_id" options=$assessmentregimes selected=$report_options.assessment_regime_id}</td>
      </tr>
      <tr>
        <td class="property">Year Seeking Placement</td>
        <td>
          <input type="text" size="5" name="year" value="{$report_options.year}">
        </td>
      </tr>
      {include file="reports/format_selector.tpl"}
{include file="reports/input_footer.tpl"}