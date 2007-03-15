{* Smarty *}
{* Template for deleting a vacancy from a company record *}
<h2 align="center">Are You Sure?</h2>
<p align="center">You have elected to delete a vacancy with description
"{$vacancy.description|escape:"htmlall"}" from the company record for
{$vacancy.company_name|escape:"htmlall"}. This should not normally be done;
instead the vacancy should be marked "closed" or "archived".
Are you sure?
</p>
<p align="center">
<a href="{$conf.scripts.company.edit}?mode=VacancyDelete&vacancy_id={$vacancy.vacancy_id}&confirmed=1">
Click here to delete
</a></p>
<p align="center">
<a href="{$conf.scripts.company.edit}?mode=COMPANY_BASICEDIT&company_id={$vacancy.company_id}">
Click here to return to the company view
</a></p>
