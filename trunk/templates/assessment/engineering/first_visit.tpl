{* Smarty *}
{* Template for Faculty of Engineering First Visit *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}

<table>
<tr>
  <th>Category</th>
  <th>5</th>
  <th>4</th>
  <th>3</th>
  <th>2</th>
  <th>1</th>
  <th>0</th>
  <th>N/A</th>
</tr>
<tr>
  <th>Personal Work Activities <br />{$assessment->flagVariable("personal_work")}</th>
  <td><input type="radio" name="personal_work" value="5" {if $assessment->getValue("personal_work")==5}CHECKED{/if}/>
    <small>Fully conversant, in-depth knowledge.</small>
  </td>
  <td><input type="radio" name="personal_work" value="4" {if $assessment->getValue("personal_work")==4}CHECKED{/if}/>
    <small>Can describe, some understanding.</small>
  </td>
  <td><input type="radio" name="personal_work" value="3" {if $assessment->getValue("personal_work")==3}CHECKED{/if}/>
    <small>Brief detail, little enquiry evident.</small>
  </td>
  <td><input type="radio" name="personal_work" value="2" {if $assessment->getValue("personal_work")==2}CHECKED{/if}/>
    <small>Very brief, shall, little understanding.</small>
  </td>
  <td><input type="radio" name="personal_work" value="1" {if $assessment->getValue("personal_work")==1}CHECKED{/if}/>
    <small>Very brief, no understanding, lacks interest.</small>
  </td>
  <td><input type="radio" name="personal_work" value="0" {if $assessment->getValue("personal_work")==0}CHECKED{/if}/>
    <small>Unable to describe</small>
  </td>
  <td>
  </td>
</tr>
{* Personal Development Plan *}
<tr>
  <th>Personal Development Plan <br />{$assessment->flagVariable("personal_development")}</th>
  <td><input type="radio" name="personal_development" value="5" {if $assessment->getValue("personal_development")==5}CHECKED{/if}/>
    <small>Well developed, clear outcomes, takes ownership, discusses.</small>
  </td>
  <td><input type="radio" name="personal_development" value="4" {if $assessment->getValue("personal_development")==4}CHECKED{/if}/>
    <small>Has an outline of the placement outcomes, shallow interest.</small>
  </td>
  <td><input type="radio" name="personal_development" value="3" {if $assessment->getValue("personal_development")==3}CHECKED{/if}/>
    <small>Brief on the plan, unclear on outcomes.</small>
  </td>
  <td><input type="radio" name="personal_development" value="2" {if $assessment->getValue("personal_development")==2}CHECKED{/if}/>
    <small>Has not developed plan, intends to, sees the need.</small>
  </td>
  <td><input type="radio" name="personal_development" value="1" {if $assessment->getValue("personal_development")==1}CHECKED{/if}/>
    <small>No plan yet, will develop if necessary.</small>
  </td>
  <td><input type="radio" name="personal_development" value="0" {if $assessment->getValue("personal_development")==0}CHECKED{/if}/>
    <small>No plan, not interested.</small>
  </td>
  <td>
  </td>
</tr>
{* Placement Organisation Knowledge *}
<tr>
  <th>Placement Organisation Knowledge <br />{$assessment->flagVariable("organisation_knowledge")}</th>
  <td><input type="radio" name="organisation_knowledge" value="5" {if $assessment->getValue("organisation_knowledge")==5}CHECKED{/if}/>
    <small>Fully conversant with organisation, products, processes, markets.</small>
  </td>
  <td><input type="radio" name="organisation_knowledge" value="4" {if $assessment->getValue("organisation_knowledge")==4}CHECKED{/if}/>
    <small>Good working knowledge of organisation, products, processes.</small>
  </td>
  <td><input type="radio" name="organisation_knowledge" value="3" {if $assessment->getValue("organisation_knowledge")==3}CHECKED{/if}/>
    <small>Knows a few facts directly relevant to work activities.</small>
  </td>
  <td><input type="radio" name="organisation_knowledge" value="2" {if $assessment->getValue("organisation_knowledge")==2}CHECKED{/if}/>
    <small>Little knowledge outside immediate area.</small>
  </td>
  <td><input type="radio" name="organisation_knowledge" value="1" {if $assessment->getValue("organisation_knowledge")==1}CHECKED{/if}/>
    <small>Has not enquired about the organisation, will do if necessary.</small>
  </td>
  <td><input type="radio" name="organisation_knowledge" value="0" {if $assessment->getValue("organisation_knowledge")==0}CHECKED{/if}/>
    <small>Does not know the organisation, not interested.</small>
  </td>
  <td>
  </td>
</tr>
{* Student Log Book *}
<tr>
  <th>Student Log Book <br />{$assessment->flagVariable("log_book")}</th>
  <td><input type="radio" name="log_book" value="5" {if $assessment->getValue("log_book")==5}CHECKED{/if}/>
    <small>Comprehensive detailed, reflective, up-to-date.</small>
  </td>
  <td><input type="radio" name="log_book" value="4" {if $assessment->getValue("log_book")==4}CHECKED{/if}/>
    <small>Considerable detail, some reflection, reasonably up-to-date.</small>
  </td>
  <td><input type="radio" name="log_book" value="3" {if $assessment->getValue("log_book")==3}CHECKED{/if}/>
    <small>Some details, some reflection, some gaps.</small>
  </td>
  <td><input type="radio" name="log_book" value="2" {if $assessment->getValue("log_book")==2}CHECKED{/if}/>
    <small>Little detail, brief reflection, rarely maintained.</small>
  </td>
  <td><input type="radio" name="log_book" value="1" {if $assessment->getValue("log_book")==1}CHECKED{/if}/>
    <small>Incomplete in detail, few entries, little or no reflection.</small>
  </td>
  <td><input type="radio" name="log_book" value="0" {if $assessment->getValue("log_book")==0}CHECKED{/if}/>
    <small>Does not have a Log Book.</small>
  </td>
  <td><input type="radio" name="log_book" value="" {if $assessment->getValue("log_book")==""}CHECKED{/if}/>
    <small>Not Applicable. (e.g. remote visit, no electronic log book)</small>
  </td>
  
</tr>
</table>
{* Supervisor details *}
<h3>Supervisor Details</h3>
<p>If the supervisor details were not visible to you, please ask the student
to fill the details in without delay in the form on their home page.</p>

<h3>Health &amp; Safety</h3>Enigmail
<p>Please check that the student has completed their health &amp; safety assessment
using the on-line form, and if not, ask them to complete it without delay.</p>
<table>
<tr>
  <th>Health &amp; Safety<br />Matters Raised {$assessment->flagVariable("hs_matters")}</th>
  <td><textarea name="hs_matters" rows="10" cols="60" wrap="VIRTUAL">{$assessment->getValue("hs_matters")|escape:"htmlall"}</textarea></td>
</tr>
<tr>
  <th>Health &amp; Safety<br />Advice Given {$assessment->flagVariable("hs_advice")}</th>
  <td><textarea name="hs_advice" rows="10" cols="60" wrap="VIRTUAL">{$assessment->getValue("hs_advice")|escape:"htmlall"}</textarea></td>
</tr>
</table>
<h3>Other Details</h3>
<table>
<tr>
  <th>Student Living Accommodation{$assessment->flagVariable("accommodation")}</th>
  <td><textarea name="accommodation" rows="10" cols="60" wrap="VIRTUAL">{$assessment->getValue("accommodation")|escape:"htmlall"}</textarea></td>
</tr>
<tr>
  <th>Comments on and for the Student {$assessment->flagVariable("comment_student")}</th>
  <td><textarea name="comment_student" rows="10" cols="60" wrap="VIRTUAL">{$assessment->getValue("comment_student")|escape:"htmlall"}</textarea></td>
</tr>
<tr>
  <th>Comments on the Placement {$assessment->flagVariable("comment_placement")}</th>
  <td><textarea name="comment_placement" rows="10" cols="60" wrap="VIRTUAL">{$assessment->getValue("comment_placement")|escape:"htmlall"}</textarea></td>
</tr>
<tr>
  <th>Visit conducted</th>
  <td>
    <input type="radio" name="visit" value="On" {if $assessment->getValue("visit")} CHECKED {/if}> In Person
    <input type="radio" name="visit" value="" {if !$assessment->getValue("visit")} CHECKED {/if}> Remotely (e.g. by telephone)
  </td>
</tr>
<tr>
  <th>Assessment Date<br />(DD/MM/YYYY) {$assessment->flagVariable("visit_date")}</th>
  <td><input type="text" name="visit_date" value="{$assessment->getValue("visit_date")}" /></td>
</tr>  
</table>
<input type="SUBMIT" name="BUTTON" value="Submit">

{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}
