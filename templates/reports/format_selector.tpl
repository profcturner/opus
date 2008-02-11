{* Smarty *}

{* Used to determine the output format for a specific report *}
{* "standalone" set to true means the whole table is printed, otherwise a row is inserted *}
{if $standalone}
<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="section" value="information" />
    <input type="hidden" name="function" value="report_input_do" />
    <input type="hidden" name="input_stage" value="{$input_stage}" />

    <table>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="{if $input_stage == $input_stages}{#finish#}{else}{#next#}{/if}" /></td>
      </tr>
{/if}
      <tr>
        <td class="property">Output Format</td>
        <td>
          {html_options name="output_format" options=$formats selected=$report_options.output_format}
        </td>
      </tr>
{if $standalone}
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="{if $input_stage == $input_stages}{#finish#}{else}{#next#}{/if}" /></td>
      </tr>
    </table>
  </form>
</div>
{/if}