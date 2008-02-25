{* Smarty *}

{if $is_student}
{$help_prompter->display("StudentAddCompany")}
{/if}

<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
{if $is_student}
    <input type="hidden" name="section" value="placement" />
{else}
    <input type="hidden" name="section" value="directories" />
{/if}
{if $mode == 'add'}
    <input type="hidden" name="function" value="add_application_do" />
{/if}
{if $mode == 'edit'}
    <input type="hidden" name="function" value="edit_application_do" />
    <input type="hidden" name="id" value="{$application->id}" />
{/if}
    <input type="hidden" name="company_id" value="{$application->company_id}" />
    <input type="hidden" name="vacancy_id" value="{$application->vacancy_id}" />
    <input type="hidden" name="student_id" value="{$application->student_id}" />

    <table>
      <tr>
        <td colspan="2" class="button">
          <input type="submit" class="submit" value="{$mode}" />
        </td>
      </tr>
      <tr>
        <td class="property">Company Name</td>
        <td>{$application->_company_id|escape:"htmlall"}</td>
      </tr>
      <tr>
        <td class="property">Vacancy Description</td>
        <td>{$application->_vacancy_id|escape:"htmlall"}</td>
      </tr>
      <tr>
        <td class="property">CV</td>
        <td>
          {html_options name="cv_ident" options=$cv_options selected=$selected_cv_ident}
        </td>
      </tr>
      <tr>
        <td class="property">e-Portfolio</td>
        <td>
          {html_options name="portfolio_ident" options=$eportfolio_list selected=$selected_eportfolio_list}
        </td>
      </tr>
      <tr>
        <td class="property">Cover Letter</td>
        <td>
          <textarea name="cover" rows="10" cols="60" wrap="virtual">{$application->cover}</textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button">
          <input type="submit" class="submit" value="{$mode}" />
        </td>
      </tr>
    </form>
  </table>
</div>

{if $invalid}
{#invalid_cvs#}
<div id="table_list">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th>Description</th>
    <th>Problem</th>
    <th>Approval</th>
  </tr>
  {section name=cv_list loop=$cv_list}
  {if !$cv_list[cv_list]->valid}
  <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
    <td>{$cv_list[cv_list]->description|escape:"htmlall"}</td>
    <td>{if $cv_list[cv_list]->valid}Valid{else}{$cv_list[cv_list]->problem}{/if}</td>
    <td>{if $cv_list[cv_list]->approval}Approved{else}Unapproved{/if}</td>
  </tr>
  {/if}
  {/section}
</table>
</div>
{/if}
