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
          <input type="text" name="search" size="20" value="{$form_options.search} "/>
        </td>
      </tr>
      <tr>
        <td class="property">For placement in year </td>
        <td>
          <input type="text" name="year" size="5" value="{$form_options.year}"/>
        </td>
      </tr>
      <tr>
        <td class="property">Activities</td>
        <td>
        {html_checkboxes name="activities" options=$activity_types selected=$form_options.activities separator="<br />"}
        </td>
      </tr>
      <tr>
        <td class="property">Other Options</td>
        <td>
        {html_checkboxes name="other_options" options=$other_options selected=$form_options.other_options separator="<br />"}
        </td>
      </tr>
      <tr>
        <td class="property">Sort Criterion</td>
        <td>
          {html_radios name="sort" output=$sort_types|capitalize values=$sort_types selected=$form_options.sort|default:"name"}
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="search" /></td>
      </tr>
    </table>
  </form>
</div>