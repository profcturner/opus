{* Smarty *}

{literal}
<script language="JavaScript" type="text/javascript">
<!--
function toggleAll(checked)
{
  for (i = 0; i < document.search.elements.length; i++) {
    if (document.search.elements[i].name.indexOf('schools') >= 0) 
    {
      document.search.elements[i].checked = checked;
    }
  }
}
// -->
</script>
{/literal}

<h3>{#simple_search#}</h3>
{foreach from=$letters item=letter}
<a href="?section=directories&function=simple_search_staff&initial={$letter}">{$letter}</a>
{/foreach}

<h3>{#advanced_search#}</h3>
<div id="table_manage">
  <form method="post" name="search" action="">
    <input type="hidden" name="section" value="directories">
    <input type="hidden" name="function" value="search_staff">

    <table class="table_manage">
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="search" /></td>
      </tr>
      <tr>
        <td class="property">Search For</td>
        <td>
          <input type="text" name="search" size="20" value="{$form_options.search}" />
        </td>
      </tr>
      <tr>
        <td class="property">Schools</td>
        <td>
        <a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
        {html_checkboxes name="schools" options=$schools selected=$form_options.schools separator="<br />"}
        <a href="" onclick="toggleAll(true); return false;" onmouseover="status='Select all'; return true;">Select All</a> |
        <a href="" onclick="toggleAll(false); return false;" onmouseover="status='Select all'; return true;">Deselect All</a><br />
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="search" /></td>
      </tr>
    </table>
  </form>
</div>