{* Smarty *}

<h3>{#simple_search#}</h3>
{foreach from=$letters item=letter}
<a href="?section=directories&function=simple_search_contact&initial={$letter}">{$letter}</a>
{/foreach}

<h3>{#advanced_search#}</h3>
<div id="table_manage">
  <form method="post" name="search" action="">
    <input type="hidden" name="section" value="directories">
    <input type="hidden" name="function" value="search_contacts">

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
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="search" /></td>
      </tr>
    </table>
  </form>
</div>