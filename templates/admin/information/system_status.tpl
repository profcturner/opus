{* Smarty *}

<form action="" method="post">
  <input type="hidden" name="section" value="information">
  <input type="hidden" name="function" value="system_status">
  Show up to the
  <input type="text" name="max_users" value="{$max_users|default:10}" size="3">
  most recent users from each category
  <input type="submit" value="Show">
</form>

<h3>Root (Super Admin) Users</h3>
{section name=root loop=$root_users}
{$root_users[root]->salutation}
{$root_users[root]->firstname}
{$root_users[root]->lastname},
{$root_users[root]->online}
{/section}

<h3>Student (Super Admin) Users</h3>
{section name=student loop=$student_users}
{$student_users[student]->salutation}
{$student_users[student]->firstname}
{$student_users[student]->lastname},
{$student_users[student]->online}
<br />
{/section}

{include file="list.tpl"}

<h3>User Totals</h3>

<div id="table_manage">
  <table>
    <tr>
      <td class="property">Root Users</td>
      <td>{$root_count}</td>
    </tr>
    <tr>
      <td class="property">Admin Users</td>
      <td>{$admin_count}</td>
    </tr>
    <tr>
      <td class="property">Company HR Users</td>
      <td>{$company_count}</td>
    </tr>
    <tr>
      <td class="property">Workplace Supervisor Users</td>
      <td>{$supervisor_count}</td>
    </tr>
    <tr>
      <td class="property">Academic Staff Users</td>
      <td>{$staff_count}</td>
    </tr>
    <tr>
      <td class="property">Student Users</td>
      <td>{$student_count}</td>
    </tr>
    <tr>
      <td class="property">Total</td>
      <td><strong>{$total_count}</strong></td>
    </tr>
  </table>
</div>

