{* Smarty *}

<div id="table_manage">
  <form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="section" value="directories" />
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
          {html_options name="cv_id" options=$cv_templates}
        </td>
      </tr>
      <tr>
        <td class="property">e-Portfolio</td>
        <td>
          {html_options name="portfolio_id" options=$portfolios}
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
