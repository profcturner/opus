{#instructions#}<br /><br />

<form method="POST" action="{#application_url#}?section=home&function=request_recover_password_do">
 
     <table id="table_manage">
      <tr>
        <td class="property">{#email_text#}</td>
        <td>
          <input type="text" name="recovery_email" size="20" />
        </td>
      </tr>
      <tr>
        <td colspan="2" class="button"><input type="submit" class="submit" value="recover" /></td>
      </tr>  
</table>
