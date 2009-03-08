{* Smarty *}

<form action="" method="post">
  <input type="hidden" name="section" value="information">
  <input type="hidden" name="function" value="system_status">
  Show up to the
  <input type="text" name="max_users" value="{$max_users|default:10}" size="3">
  most recent users from each category
  <input type="submit" value="Show">
</form>

{#quick_links#}

{#root_users#}
{include file="list.tpl" objects=$root_users headings=$root_headings actions=$admin_actions}
{#back_to_top#}

{#admin_users#}
{include file="list.tpl" objects=$admin_users headings=$admin_headings actions=$admin_actions}
{#back_to_top#}

{#staff_users#}
{include file="list.tpl" objects=$staff_users headings=$staff_headings actions=$staff_actions}
{#back_to_top#}

{#contact_users#}
{include file="list.tpl" objects=$contact_users headings=$contact_headings actions=$contact_actions}
{#back_to_top#}

{#supervisor_users#}
{include file="list.tpl" objects=$supervisor_users headings=$supervisor_headings actions=$supervisor_actions}
{#back_to_top#}

{#student_users#}
{include file="list.tpl" objects=$student_users headings=$student_headings actions=$student_actions}
{#back_to_top#}

<h3>User Totals</h3>

<div id="table_manage">
  <table>
    <tr>
      <th>User class</th>
      <th>Number</th>
      <th>Online</th>
    </tr>
    <tr>
      <td class="property">Root Users</td>
      <td>{$root_count}</td>
      <td>{$online_user_count.root}</td>
    </tr>
    <tr>
      <td class="property">Admin Users</td>
      <td>{$admin_count}</td>
      <td>{$online_user_count.admin}</td>
    </tr>
    <tr>
      <td class="property">Company HR Users</td>
      <td>{$company_count}</td>
      <td>{$online_user_count.company}</td>
    </tr>
    <tr>
      <td class="property">Workplace Supervisor Users</td>
      <td>{$supervisor_count}</td>
      <td>{$online_user_count.supervisor}</td>
    </tr>
    <tr>
      <td class="property">Academic Staff Users</td>
      <td>{$staff_count}</td>
      <td>{$online_user_count.staff}</td>
    </tr>
    <tr>
      <td class="property">Student Users</td>
      <td>{$student_count}</td>
      <td>{$online_user_count.student}</td>
    </tr>
    <tr>
      <td class="property">Total</td>
      <td><strong>{$total_count}</strong></td>
      <td></td>
    </tr>
  </table>
</div>

