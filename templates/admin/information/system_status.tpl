{* Smarty *}

<form action="" method="post">
  <input type="hidden" name="section" value="information">
  <input type="hidden" name="function" value="system_status">
  Show up to the
  <input type="text" name="max_users" value="{$max_users|default:10}" size="3">
  most recent users from each category
  <input type="submit" value="Show">
</form>

<h2>Root (Super Admin) Users</h2>
{section name=root loop=$root_users}
{$root_users[root]->salutation}
{$root_users[root]->firstname}
{$root_users[root]->lastname},
{$root_users[root]->online}
{/section}

<h2>User Totals</h2>

<table>
  <tr>
    <th>Root Users</th>
    <td>{$root_count}</td>
  </tr>
  <tr>
    <th>Admin Users</th>
    <td>{$admin_count}</td>
  </tr>
  <tr>
    <th>Company HR Users</th>
    <td>{$company_count}</td>
  </tr>
  <tr>
    <th>Workplace Supervisor Users</th>
    <td>{$supervisor_count}</td>
  </tr>
  <tr>
    <th>Academic Staff Users</th>
    <td>{$staff_count}</td>
  </tr>
  <tr>
    <th>Student Users</th>
    <td>{$student_count}</td>
  </tr>
  <tr>
    <th>Total</th>
    <td>{$total_count}</td>
  </tr>
</table>

