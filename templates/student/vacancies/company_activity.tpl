{* Smarty *}

<form action="" method="post">
  <input type="hidden" name="section" value="vacancies">
  <input type="hidden" name="function" value="vacancy_home">
  Show activity in the last
  <input type="text" name="days" value="{$days|default:7}" size="3">
  days
  <input class="button" type="submit" value="Show">
</form>

{#showing_changes_since#} {$since|date_format:"%e %B %Y"}

{if $vacancies_created}
<h3>Vacancies Created</h3>
{include file="list.tpl" objects=$vacancies_created headings=$vacancy_headings actions=$vacancy_actions}
{/if}

{if $vacancies_modified}
<h3>Vacancies Modified</h3>
{include file="list.tpl" objects=$vacancies_modified headings=$vacancy_headings actions=$vacancy_actions}
{/if}

{if $companies_created}
<h3>Companies Created</h3>
{include file="list.tpl" objects=$companies_created headings=$company_headings actions=$company_actions}
{/if}

{if $companies_modified}
<h3>Companies Modified</h3>
{include file="list.tpl" objects=$companies_modified headings=$company_headings actions=$company_actions}
{/if}

{if !$vacancies_created && !$vacancies_modified && !$companies_created && !$companies_modified}
{#no_changes#}
{/if}
