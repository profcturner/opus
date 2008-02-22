{* Smarty *}

{$help_prompter->display("ContactHome")}

<br />

<h3>{#your_companies#}</h3>
{foreach name=companies from=companies key=company_id item=company_name}
  {if $smarty.foreach.companies.first}
  <div id="table_list">
    <table cellpadding="0" cellspacing="0" border="0">
      <tr>
        <th>{#company_name#}</th>
        <th class="action">select</th>
      </tr>
  {/if}
      <tr>
        <td>{$company_name|escape:"htmlall"}</td>
        <td class="action"><a href="?section=my_company&function=edit_company&company_id={$company_id}">select</a></td>
      </tr>
  {if $smarty.foreach.companies.last}
    </table>
  </div>
  {/if}
{sectionelse}
  {#no_companies#}
{/section}


