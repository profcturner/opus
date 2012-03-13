{* Smarty *}
{*
{#last_login#} {$user.last_login}<br />
{if $user.user_type == "root"}
{$help_prompter->display("RootHome")}
{else}
{$help_prompter->display("AdminHome")}
{/if}
*}

<div id="main"> <!-- main content area start -->
<div id="dashboard"><!-- dasboard starts -->
<table>
	<tr>
	<td>
      <div id="preabmle"> <!-- preamble start -->
      
		  <div class="preamble_small">
			 {#last_login#} {$user.last_login}<br />
		  </div>
		  <br />
		  <div class="preamble_small">Don't forget to review your system <a href="?section=main&function=edit_preferences" title="Preferences" class="thickbox"><em class="warning">preferences</em></a>, they can help you personalise the {$config.opus.title_short} system.
		  </div>
      
      </div> <!-- preamble ends -->
	</td>
	</tr>
	
<table>	
	<tr>
	<td>
		  
			<table>
				<tr>
		{if $user.user_type == "root"}
			{$help_prompter->display("RootHome")}
		{else}
			{$help_prompter->display("AdminHome")}
		{/if}   
				</tr>
			</table>
	</td>
	</tr>
  
</table>
      
    </td>
    </tr>
</table>
  
</div><!-- dashboard ends -->
      
      <div id="dashboard_buttons">
      		<table>
              <tr>
					<td colspan="2" align="center" style="padding-bottom:30px; font-weight:bold">
						<em>These are your short-cut buttons</em> 
					</td>
			  </tr>
              <tr>
                <td id="button_left" nowrap><a href="?section=directories&function=company_directory&page=1">companies</a></td>
             
                <td id="buttons" nowrap><a href="?section=directories&function=staff_directory&page=1">academic staff</a></td>
           
                <td id="buttons" nowrap><a href="?section=information&function=list_reports&page=1">reports</a></td>
			
                <td id="buttons" nowrap><a href="?section=information&function=system_status&page=1">system status</a></td>
			
                <td id="buttons" nowrap><a href="?section=superuser&function=user_directory&page=1">user directory</a></td>
         
              </tr></center>
			</table>
      </div>
      
</div> <!-- main content area end-->
