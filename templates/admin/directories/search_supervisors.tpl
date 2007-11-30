{* Smarty *}

  <div id="table_list">
    <table cellpadding="0" cellspacing="0" border="0">
      <tr>
        <th>Name</th>
        <th>Company</th>
        <th>Vacancy</th>
        <th>Student</th>
        <th class="action">Reset Password</th>
        <th class="action">Placement</th>
      </tr>
      {section name=object loop=$objects}
      <tr class="{cycle name="cycle1" values="dark_row,light_row"}">
        <td>{$objects[object]->real_name|escape:"htmlall"}</td>
        <td>{$objects[object]->_company_id|escape:"htmlall"}</td>
        <td>{$objects[object]->_vacancy_id|escape:"htmlall"}</td>
        <td>{$objects[object]->_student_id|escape:"htmlall"}</td>
        <td class="action"><a href="?section=directories&function=reset_password&user_id={$objects[object]->user_id}&done_function=supervisor_directory&error_function=supervisor_directory">reset password</a></td>
        <td class="action"><a href="?section=directories&function=edit_student&id={$objects[object]->student_id}">placement</a></td>
      </tr>
      {sectionelse}
      {#none_found#}
      {/section}
    </table>
  </div>
