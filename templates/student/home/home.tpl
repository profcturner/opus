{* Smarty *}

{*
{#last_login#} {$user.opus.last_login}<br />
{$help_prompter->display("StudentHome", $student->user_id)}
{eval assign="student_year" var="StudentHome"|cat:$student->placement_year} 
{$help_prompter->display($student_year, $student->user_id)}
{if $student->placement_status == 'Placed'}
{$help_prompter->display("StudentHomePlaced", $student->user_id)}
{/if}
{eval assign="student_year" var="StudentHomePlaced"|cat:$student->placement_year} 
{if $student->placement_status == 'Placed'}
{$help_prompter->display($student_year, $student->user_id)}
{/if}
*}

<script type="text/javascript" src='../../opus/javascript/jquery.min.js'}/></script>
<script type="text/javascript" src='../../opus/javascript/bubble.js'/></script>

<div id="main"> <!-- main content area start -->
<div id="dashboard"><!-- dasboard starts -->
<table><tr><td>
      <div id="preabmle"> <!-- preamble start -->

      {*<div class="photo"><a href="?function=view_photo" ><img src="?function=view_photo" height="100px" border="0"></a></div>*}
      <div class="preamble">
		 {$user.opus.firstname} {$user.opus.lastname}'s OPUS
      </div>
      <br />
       <div class="preamble_small">Don't forget to review your system <a href="?section=main&function=edit_preferences" title="Preferences" class="thickbox"><em class="warning">preferences</em></a>, they can help you personalise the {$config.opus.title_short} system.</div>
       

        </div> <!-- preamble ends -->
 </td><tr>
    <table>
    <tr>
      <td>
      	<table>
      		<tr>
                <td colspan="2" align="center" style="padding-bottom:5px; font-weight:bold">
                  <em>Hover over the images below to check what you have on OPUS</em> 
                </td>
              </tr>
           </table>
      
      <table>
        <tr>
          <td>
           <table class="summary_table_component">
                    <tr>
                    <td width="150px">
                    <div class="bubbleInfo">
					  <center><img class="trigger" src="../../opus/images/logo.jpg" /></center>
						<center><table><tr>
						  <th id="bubbleInfoTitle" colspan="2"> Opportunities </th>
						</tr>
						</table></center>
					  <div class="popup">
						<table>
								<tr><th>Last Login</th>
								</tr>
								<tr>
									<td>{if $user.opus.last_login_time != "0000-00-00 00:00:00"}
									  {$user.opus.last_login_time|date_format:"%d-%m-%Y"}
									  at
									  {$user.opus.last_login_time|date_format:"%I:%M %p"}
									  {else}
									  <em>First login!</em>
									  {/if}
									</td>
								</tr>
								<tr>
									<th>Disk Usage</th>
								</tr>
								<tr>
								<td>
										<table class="usage_graph">
												  {if $disk_size}
												  {math equation="x*y"  x=$disk_size y=1024  assign=disk_size_k}
												  {if  $disk_used > $disk_size_k}
											<tr class="nopad">
												<td class="nopad" bgcolor="#ff0000" height="10px" width="250px" align="left" colspan="2"></td>
											</tr>
												  {else}
											<tr class="nopad">
												<td class="nopad" bgcolor="#ddaaaa" height="10px" width="{math equation="r*250" r=$graph_red}px" align="left"></td>
												<td bgcolor="#aaddaa" height="10px" width="{math equation="g*250" g=$graph_green}px" align="right"></td>
											</tr>
												  {/if}
												  {/if}
											<tr>
												<td colspan="2">{$storage_info.diskspace_used|string_format:"%.1f"}K (remaining:{$storage_info.diskspace_free|string_format:"%.1f"}K)
												</td>
											</tr>
											
										</table>
									</td>
								</tr>
								<tr>
                            		<td colspan="2">
                              				<table>
												<tr nowrap>
													<td nowrap>
													  {section name=usage start=0 loop=$storage_info.diskspace_graph_count max=20}<img border="0" src="images/disk_used.gif" />{/section}{section name=usage start=$storage_info.diskspace_graph_count loop=20}<img border="0" src="images/disk_free.gif" />{/section}
													 </td>
													
												</tr>
											</table>
				
											
                            		</td>
                          		</tr>
                     		 </table>
					  </div>
					</div>
					</td>
					
                     <td width="150px"><div class="bubbleInfo">
						  <a href="?section=home&function=list_messages&mode=inbox&page=1"><img style="border-style: none"; class="trigger" src="../../opus/images/logo.jpg"/></a>
						  	<center><table id="messages"><tr>
						  		<th id="bubbleInfoTitle" colspan="2"> CV's </th>
							</tr>
							</table></center>
						  	<div class="popup">
						  		<table>
						  		<tr>
						  		</th><center><b>Messages</b></center></th>
						  		</tr>
						  		<tr>
								<td>New Messages</td>
								 <td><a href="?section=home&function=list_messages&mode=inbox&page=1"><em class="message_count">
											{$new_message_count}
										  </em></a>
								</td>
								</tr>
								</table>
								
					<td width="150px"><div class="bubbleInfo">
						  <a href="?section=portfolios&function=manage_portfolios&page=1"><img class="trigger" src="../../opus/images/logo.jpg" style="border-style: none";/></a>
						  	<center><table id="messages"><tr>
						  		<th id="bubbleInfoTitle" colspan="2"> e-Portfolios </th>
							</tr>
							</table></center>
						  	<div class="popup">
						  		{if $portfolios|@count > 0}
									<table>
									<tr>
									<th>e-Portfolios</th>
									</tr>
									  <tr>
										<td><small>comments</small></td>
									  </tr>
									  {if $portfolios|@count < 8}
									  {section loop=$portfolios name=portfolio}
									  <tr>
										<td><a target="_blank" href="?section=portfolios&function=view_portfolio&id={$portfolios[portfolio]->id}&page=1">{$portfolios[portfolio]->name}</a></td><td><a href="?section=home&function=list_messages&mode=inbox&filter=subject=%22Portfolio Comments: {$portfolios[portfolio]->name}%22">{$portfolios[portfolio]->_comment_count} {if $portfolios[portfolio]->_comment_count_new > 0}({$portfolios[portfolio]->_comment_count_new}){/if}</a></td>
									  </tr>
									  {/section}
									  {else}
									  {section loop=$portfolios name=portfolio max=7}
									  <tr>
										<td><a target="_blank" href="?section=portfolios&function=view_portfolio&id={$portfolios[portfolio]->id}&page=1">{$portfolios[portfolio]->name}</a></td><td><a href="?section=home&function=list_messages&mode=inbox&filter=subject=%22Portfolio Comments: {$portfolios[portfolio]->name}%22">{$portfolios[portfolio]->_comment_count} {if $portfolios[portfolio]->_comment_count_new > 0}({$portfolios[portfolio]->_comment_count_new}){/if}</a></td>
									  </tr>
									  {/section}
									  <tr><td colspan="2"><a href="?function=view_portfolios">more...</a></td></tr>
									  {/if}
									</table>
								{/if}
							</div>
						</div>
							</td>
							<td width="150px"> <div class="bubbleInfo">
								  <a href="?section=programme&function=view_programme_team&page=1"><img class="trigger" src="../../opus/images/logo.jpg" style="border-style: none";/></a>
									<center><table><tr>
									  <th id="bubbleInfoTitle" colspan="2"> Course Team </th>
									</tr>
									</table></center>
								  <div class="popup">
			
								  <table>
								  	<tr>
									  <th colspan="2">{$student_course.programme_title}</th>
									  </tr>
									  <tr>
									  	<td><b><i>Course Director</b></i></td><td>{$staff_course.crsdir_title}</td>
									  </tr>
								{section loop=$programme_team name=member}
								
									  <tr>
									  	<td><b><i>Course</b></i></td><td>{$programme_team[member][0]->name|capitalize}</td>
									  </tr>
										<td><b><i>{$programme_team[member][0]->role}</i></b></td><td>{$programme_team[member][1]->firstname} {$programme_team[member][1]->lastname}{/section}</td>
									  </tr>
									  {if $number_course_team < 6}
									  {section loop=$course_team name=member}
									  <tr>
										<td>{$course_team[member][1]->role}</td><td nowrap><a href="?function=view_course_team">{$course_team[member][0]->firstname} {$course_team[member][0]->lastname}</a> {*{if $course_team[member][0]->email|count_characters != 0}<a href="mailto:{$course_team[member][0]->email}">email</a>{/if}*}</td><td valign="center">{if $course_team[member][1]->notice}<img src="{#IMAGES_DIRECTORY#}/icons/notice.gif" title="a notice has been posted!">{/if}
										</td>
									  </tr>
									  {/section}
									  {else}
									  {section loop=$course_team name=member max=4}
									  <tr>
										<td>{$course_team[member][1]->role}</td><td nowrap colspan="2"><a href="?function=view_course_team">{$course_team[member][0]->firstname} {$course_team[member][0]->lastname}</a>{* {if $course_team[member][0]->email|count_characters != 0}<a href="mailto:{$course_team[member][0]->email}">email</a>{/if}*}</td><td valign="center">{if $course_team[member][1]->notice}<img src="{#IMAGES_DIRECTORY#}/icons/notice.gif" title="a notice has been posted!">{/if} </td>
									  </tr>
									  {/section}
									  <tr><td colspan="2"><a href="?function=view_course_team">more...</a></td></tr>
									  {/if}
									  
								
								</table>
									
									
									
							</td>
                    </tr>
                  </table>
          </td>
        </tr>
        <tr>
        <td>
        	 <table>
              
              <tr>
					<td colspan="2" align="center" style="padding-bottom:30px; font-weight:bold">
						<em>These are your short-cut buttons</em> 
					</td>
			  </tr>
              <tr>
                <td id="button_left" nowrap><a href="?section=career&function=manage_cvs&page=1">cv store</a></td>
             
                <td id="buttons" nowrap><a href="?section=placement&function=vacancy_directory&page=1">vacancies</a></td>
              
                <td id="buttons" nowrap><a href="?section=placement&function=list_assessments&page=1">assessment</a></td>
                
                <td id="buttons" nowrap><a href="?section=placement&function=list_resources&page=1">resources</a></td>
         
              </tr></center>
            </table>
            <br><br>
            </td>
        </tr>
{if $number_documents}
        <tr>
          <td><table class="summary_table_component">
                    <tr>
                      <th colspan="3"> Forms </th>
                    </tr>
                    <tr>
                      <td width="100px"></td>
                      <td><small>download</small></td>
                      <td><small>upload</small></td>
                    </tr>
                    {if $number_documents == 0}
                    <tr>
                      <td colspan="3"><em class="warning">No forms</em></td>
                    </tr>
                    {/if}
                    {if $number_documents < 5}
                    {counter assign="iter" start=0}
                    {section loop=$documents name=document}
                    {section loop=$documents[document][1] name=file}
                    <tr>
                      <td><a title="{$documents[document][1][file][1]->description}, Provided by: {$documents[document][0]->firstname} {$documents[document][0]->lastname}" href="?function=open_artifact&hash={$documents[document][1][file][1]->hash}" >
                        {$documents[document][1][file][1]->file_name}
                      </a></td>
                      <td><a  href="?function=open_artifact&hash={$documents[document][1][file][1]->hash}"><img src="{#IMAGES_DIRECTORY#}/icons/downarrow.png" border="0"></a></td>
                      <td><a href="?function=upload_form&form_id={$documents[document][1][file][0]->uploaded_file_id}"><img src="{#IMAGES_DIRECTORY#}/icons/uparrow.png" border="0"></a>
                          {if $submitted[$iter] == 0}
                          {else}
                          <a href="?function=list_submitted_forms&uploaded_file_id={$documents[document][1][file][0]->uploaded_file_id}" title="click to go to {$submitted[$iter]} uploaded forms"><img src="{#IMAGES_DIRECTORY#}/icons/uploaded.png" border="0"></a>
                          {/if}</td>
                    </tr>
                    {counter}
                    {/section}
                    {/section}
                    {else}
                    {counter assign="iter" start=0}
                    {section loop=$documents name=document}
                    {section loop=$documents[document][1] name=file}
                    {if $iter < 4}
                    <tr>
                      <td><a title="{$documents[document][1][file][1]->description}, Provided by: {$documents[document][0]->firstname} {$documents[document][0]->lastname}" href="?function=open_artifact&hash={$documents[document][1][file][1]->hash}" >
                        {$documents[document][1][file][1]->file_name}
                      </a></td>
                      <td align=""><a  href="?function=open_artifact&hash={$documents[document][1][file][1]->hash}" ><img src="{#IMAGES_DIRECTORY#}/icons/downarrow.png" border="0"></a></td>
                      <td align="center"><a href="?function=upload_form&form_id={$documents[document][1][file][0]->uploaded_file_id}"><img src="{#IMAGES_DIRECTORY#}/icons/uparrow.png" border="0"></a>
                          {if $submitted[$iter] == 0}
                          {else}
                          <a href="?function=list_submitted_forms&uploaded_file_id={$documents[document][1][file][0]->uploaded_file_id}" title="click to go to {$submitted[$iter]} uploaded forms"><img src="{#IMAGES_DIRECTORY#}/icons/uploaded.png" border="0"></a>
                          {/if}</td>
                    </tr>
                    {/if}
                    {counter}
                    {/section}
                    {/section}
                    <tr>
                      <td colspan="3"><a href="?function=view_downloadable_forms">more...</a></td>
                    </tr>
                    {/if}
                  </table></td>
        </tr>
{/if}
<!--        <tr>
          <td><table class="summary_table_component">
                    <tr>
                      <th colspan="2"> Course Resources </th>
                    </tr>
                    {if $number_resources == 0}
                    <tr>
                      <td colspan="2"><em class="warning">No resources</em></td>
                    </tr>
                    {/if}
                    {if $number_resources < 5}
                    {section loop=$course_resources name=resource}
                    <tr>
                      <td><a target="_blank" href="{$course_resources[resource]->url}">
                        {$course_resources[resource]->title}
                      </a></td>
                    </tr>
                    {/section}
                    {else}
                    {section loop=$course_resources name=resource max=4}
                    <tr>
                      <td><a target="_blank" href="{$course_resources[resource]->url}">
                        {$course_resources[resource]->title}
                      </a></td>
                    </tr>
                    {/section}
                    <tr>
                      <td><a href="?function=list_course_resources">more...</a></td>
                    </tr>
                    {/if}
                  </table></td>
        </tr>-->
        <!--<tr>
          <td>
            <table class="summary_table_component">
              {if $is_advisee == True}
              <tr>
                <th colspan="2">
                  Adviser Forms
                </th>
              </tr>
              {if $programme_year == 1}
              <tr>
                <td><a href="?function=view_forms">Year 1 semester 1</a></td><td>{$status_1|default:"blank"}</td>
              </tr>
              <tr>
                <td><a href="?function=view_forms">Year 1 semester 2</a></td><td>{$status_3|default:"blank"}</td>
              </tr>
              {/if}
              {if $programme_year == 2 }
              <tr>
                <td><a href="?function=view_forms">Year 2 semester 1</a></td><td>{$status_2|default:"blank"}</td>
              </tr>
              <tr>
                <td><a href="?function=view_forms">Year 2 semester 2</a></td><td>{$status_4|default:"blank"}</td>
              </tr>
              {/if}
              <tr>
                <td><a href="?function=view_forms">Additional Meeting(s) </a></td><td>{$status_10|default:"blank"}</td>
              </tr>
              {else}
              <tr>
                <th colspan="2">
                  Adviser Forms
                </th>
              </tr>
              <tr>
                <td>No <em>Adviser of Study</em> in your <a href="?function=view_course_team">Course Team</a>.</td>
              </tr>
              {/if}
            </table>
            </td>
        </tr>-->
      </table>
       
      
      
      
      </td>
  </tr> 
  </table>
  

        
  </div><!-- dashboard ends -->
      </div> <!-- main content area end-->
