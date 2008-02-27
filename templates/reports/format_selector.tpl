{* Smarty *}

{* Used to determine the output format for a specific report *}
{* "standalone" set to true means the whole table is printed, otherwise a row is inserted *}
{if $standalone}
{include file="reports/input_header.tpl"}
{/if}
      <tr>
        <td class="property">Output Format</td>
        <td>
          {html_options name="output_format" options=$formats selected=$report_options.output_format}
        </td>
      </tr>
{if $standalone}
{include file="reports/input_footer.tpl"}
{/if}