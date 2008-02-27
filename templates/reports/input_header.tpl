{* Smarty *}
{* Header on all inputs *}

<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="section" value="information" />
    <input type="hidden" name="function" value="report_input_do" />
{*    <input type="hidden" name="name" value="" value="{$unique_name}" />*}
    <input type="hidden" name="input_stage" value="{$input_stage}" />

    <table>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="{if $input_stage == $input_stages}{#finish#}{else}{#next#}{/if}" /></td>
      </tr>
