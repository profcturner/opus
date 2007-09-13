{* Smarty *}

<div id="table_manage">
  <form method="post" name="search" action="">
    <input type="hidden" name="section" value="directories">
    <input type="hidden" name="function" value="search_vacancies">
  
    <table class="table_manage">
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="search" /></td>
      </tr>
      <tr>
        <td class="property">Search For</td>
        <td>
          <input type="text" name="search" size="20" />
        </td>
      </tr>
      <tr>
        <td class="property">For placement in year </td>
        <td>
          <input type="text" name="year" size="5" />
        </td>
      </tr>
      <tr>
        <td class="property">Activities</td>
        <td>
        {html_checkboxes name="activities" options=$activity_types separator="<br />"}
        </td>
      </tr>
      <tr>
        <td class="property">Other Options</td>
        <td>
          <input type="checkbox" name="show_closed" /> Show Closed Vacancies <br />
          <input type="checkbox" name="search_companies" /> Search Companies <br />
          <input type="checkbox" name="search_vacancies" /> Search Vacancies
        </td>
      </tr>
      <tr>
        <td class="property">Sort Criterion</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="search" /></td>
      </tr>
    </table>
  </form>
</div>