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
{include file="list.tpl" objects=$root_users headings=$admin_headings}

<h3>Administrators</h3>
{include file="list.tpl" objects=$admin_users headings=$admin_headings}

<h3>Students</h3>
{include file="list.tpl" objects=$student_users}

<h3>Company HR Contacts</h3>
{include file="list.tpl" objects=$company_users}

<h3>Workplace Supervisors</h3>
{include file="list.tpl" objects=$supervisor_users}

<h3>Academic Staff</h3>
{include file="list.tpl" objects=$staff_users}




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

