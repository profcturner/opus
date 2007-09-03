{* Smarty *}

<form action="" method="post">
  <input type="hidden" name="section" value="home">
  <input type="hidden" name="function" value="company_activity">
  Show activity in the last
  <input type="text" name="days" value="{$days|default:7}" size="3">
  days
  <input type="submit" value="Show">
</form>

<h2>Vacancies Created</h2>
{section name=vacancies_created loop=$vacancies_created}
{if $smarty.section.vacancies_created.first}<ol>{/if}
  <li>{$vacancies_created[vacancies_created]->description}
<span id="action_button"><a href="{$config.opus.url}/index.php?section=directories&function=edit_vacancy&id={$vacancies_created[vacancies_created]->id}">Edit</a></span>
</li>
{if $smarty.section.vacancies_created.last}</ol>{/if}
{sectionelse}
No new vacancies.
{/section}

<h2>Vacancies Modified</h2>
{section name=vacancies_modified loop=$vacancies_modified}
{if $smarty.section.vacancies_modified.first}<ol>{/if}
  <li>{$vacancies_modified[vacancies_modified]->description} <span id="action_button"><a href="{$config.opus.url}/index.php?section=directories&function=edit_vacancy&id={$vacancies_modified[vacancies_modified]->id}">Edit</a></span></li>
{if $smarty.section.vacancies_modified.last}</ol>{/if}
{sectionelse}
No modified vacancies.
{/section}

<h2>Companies Created</h2>
{section name=companies_created loop=$companies_created}
{if $smarty.section.companies_created.first}<ol>{/if}
  <li>{$companies_created[companies_created]->name} <span id="action_button"><a href="{$config.opus.url}/index.php?section=directories&function=edit_company&id={$companies_created[companies_created]->id}">Edit</a></span></li>
{if $smarty.section.companies_created.last}</ol>{/if}
{sectionelse}
No new companies.
{/section}

<h2>Companies Modified</h2>
{section name=companies_modified loop=$companies_modified}
{if $smarty.section.companies_modified.first}<ol>{/if}
  <li>{$companies_modified[companies_modified]->name} <span id="action_button"><a href="{$config.opus.url}/index.php?section=directories&function=edit_company&id={$companies_modified[companies_modified]->id}">Edit</a></span></li>
{if $smarty.section.companies_modified.last}</ol>{/if}
{sectionelse}
No modified companies.
{/section}


