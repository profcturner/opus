{* Smarty *}

{literal}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(school, checked)
{
  for (i = 0; i < document.search.elements.length; i++) {
    if(school)
    {
      if(document.search.elements[i].value == school) document.search.elements[i].checked = checked;
    }
    else
    {
      if (document.search.elements[i].name.indexOf('cc') >= 0) 
      {
        document.search.elements[i].checked = checked;
      }
    }
  }
}
// -->
</script>
{/literal}

<h3>{#simple_search#}</h3>
{foreach from=$letters item=letter}
<a href="?section=directories&function=simple_search_student&initial={$letter}">{$letter}</a>
{/foreach}

<h3>{#advanced_search#}</h3>
<div id="table_manage">
  <form method="post" name="search" action="">
    <input type="hidden" name="section" value="directories">
    <input type="hidden" name="function" value="manage_students">

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
        <td class="property">From Programmes</td>
        <td>
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