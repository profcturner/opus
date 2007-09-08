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

<div id="table_manage">
  <form method="post" name="search" action="">
    <input type="hidden" name="section" value="directories">
    <input type="hidden" name="function" value="manage_students">
  
    <table class="table_manage">
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
        </td>
      </tr>
      <tr>
        <td class="property">Sort Criterion</td>
        <td></td>
      </tr>
    </table>
  </form>
</div>