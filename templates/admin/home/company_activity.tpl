{* Smarty *}

<form action="" method="post">
  <input type="hidden" name="section" value="home">
  <input type="hidden" name="function" value="company_activity">
  Show activity in the last
  <input type="text" name="days" value="{$days|default:7}" size="3">
  days
  <input class="button" type="submit" value="Show">
</form>

<h3>Vacancies Created</h3>
{include file="list.tpl" objects=$vacancies_created headings=$vacancy_headings actions=$vacancy_actions}

<h3>Vacancies Modified</h3>
{include file="list.tpl" objects=$vacancies_modified headings=$vacancy_headings actions=$vacancy_actions}

<h3>Companies Created</h3>
{include file="list.tpl" objects=$companies_created headings=$company_headings actions=$company_actions}

<h3>Companies Modified</h3>
{include file="list.tpl" objects=$companies_modified headings=$company_headings actions=$company_actions}
