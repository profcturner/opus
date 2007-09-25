{* Smarty *}

{literal}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(faculty, checked)
{
  for (i = 0; i < document.search.elements.length; i++) {
    if(faculty)
    {
      if(document.search.elements[i].value == school) document.search.elements[i].checked = checked;
    }
    else
    {
      if (document.search.elements[i].name.indexOf('programme') >= 0) 
      {
        document.search.elements[i].checked = checked;
      }
    }
  }
}

function toggleFaculty(faculty, checked)
{
  var faculty_div = document.getElementById(faculty);
  alert(faculty);
  alert(checked);
  alert("deployed");
  alert(faculty_div.elements.length);
  for(i = 0; i < faculty_div.elements.length; i++)
  {
    if(faculty_div.elements[i].name.indexOf("programme") >= 0)
    {
      faculty_div.elements[i].checked = checked;
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
    <input type="hidden" name="function" value="search_students">

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
        <a href="" onclick="toggleAll(0, true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleAll(0, false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
        {foreach from=$structure item=faculty}
        <div id="faculty_{$faculty.id}" name="faculty_{$faculty.id}">
        <strong>{$faculty.name}</strong><small> (<a href="" onclick="toggleFaculty(this, true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleFaculty('faculty_{$faculty.id}', false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a>)</small><br />
          {foreach from=$faculty.schools item=school}
            <em>&nbsp;&nbsp;{$school.name}</em><br />
              {html_checkboxes name="programmes" options=$school.programmes selected=$form_options.programmes separator="<br />"}
          {/foreach}
        </div> <!-- faculty_{$faculty.id} -->
        {/foreach}
        <a href="" onclick="toggleAll(0, true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleAll(0, false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
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
          {html_radios name="sort" options=$sort_types selected=$form_options.sort|default:"name"}
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="search" /></td>
      </tr>
    </table>
  </form>
</div>