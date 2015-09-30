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

function toggleByDiv(divid, checked)
{
  var inputfields = document.getElementById(divid).getElementsByTagName('input');
  for (var i = 0; i < inputfields.length; i++)
  {
    if (inputfields[i].type.toUpperCase()=='CHECKBOX')
    {
      inputfields[i].checked = checked;
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
        <td class="property">{#search_for#|capitalize}</td>
        <td>
          <input type="text" name="search" size="20" value="{$form_options.search}" />
        </td>
      </tr>
      <tr>
        <td class="property">{#from_placement_year#|capitalize}</td>
        <td>
          <input type="text" name="year" size="5" value="{$form_options.year}" />
        </td>
      </tr>
      <tr>
        <td class="property">{#from_programmes#|capitalize}</td>
        <td>
        <a href="" onclick="toggleAll(0, true); return false;" onmouseover="status='{#select_all#|capitalize}'; return true;">{#select_all#|capitalize}</a> |
        <a href="" onclick="toggleAll(0, false); return false;" onmouseover="status='{#deselect_all#|capitalize}'; return true;">{#deselect_all#|capitalize}</a><br />
        {foreach from=$structure item=faculty}
        <div id="faculty_{$faculty.id}" name="faculty_{$faculty.id}">
        <strong>{$faculty.name}</strong><small> (<a href="" onclick="toggleByDiv('faculty_{$faculty.id}', true); return false;" onmouseover="status='{#select_all#|capitalize}'; return true;">{#select_all#|capitalize}</a> |
        <a href="" onclick="toggleByDiv('faculty_{$faculty.id}', false); return false;" onmouseover="status='{#deselect_all#|capitalize}'; return true;">{#deselect_all#|capitalize}</a>)</small><br />
          {foreach from=$faculty.schools item=school}
            <div id="school_{$school.id}" name="school_{$school.id}">
            <em>&nbsp;&nbsp;{$school.name}</em><small> (<a href="" onclick="toggleByDiv('school_{$school.id}', true); return false;" onmouseover="status='{#select_all#|capitalize}'; return true;">{#select_all#|capitalize}</a> |
        <a href="" onclick="toggleByDiv('school_{$school.id}', false); return false;" onmouseover="status='{#deselect_all#|capitalize}'; return true;">{#deselect_all#|capitalize}</a>)</small><br />
              {html_checkboxes name="programmes" options=$school.programmes selected=$form_options.programmes separator="<br />"}
            </div>
          {/foreach}
        </div> <!-- faculty_{$faculty.id} -->
        {/foreach}
        <a href="" onclick="toggleAll(0, true); return false;" onmouseover="status='{#select_all#|capitalize}'; return true;">{#select_all#|capitalize}</a> |
        <a href="" onclick="toggleAll(0, false); return false;" onmouseover="status='{#deselect_all#|capitalize}'; return true;">{#deselect_all#|capitalize}</a><br />
        </td>
      </tr>
      <tr>
        <td class="property">{#other_options#|capitalize}</td>
        <td>
        {html_checkboxes name="other_options" options=$other_options selected=$form_options.other_options separator="<br />"}
        </td>
      </tr>
      <tr>
        <td class="property">{#sort_criterion#|capitalize}</td>
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