{* Smarty *}
{* Template for Faculty of Engineering First Visit *}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}

<div id="table_manage">
<table>

{* Productivity *}

<tr>
  <td class="property">Productivity <br />{$assessment->flag_error("productivity")}</td>
  <td><input type="radio" name="productivity" value="6" {if $assessment->get_value("productivity")==6}CHECKED{/if}/>
    <small>Student's output makes a <strong>significant net contribution</strong> to the company or department.</small>
  </td>
  <td><input type="radio" name="productivity" value="3" {if $assessment->get_value("productivity")==3}CHECKED{/if}/>
    <small>Student's output makes a <strong>net contribution</strong> to the company or department.</small>
  </td>
  <td><input type="radio" name="productivity" value="2" {if $assessment->get_value("productivity")==2}CHECKED{/if}/>
    <small>Student's output is <strong>acceptable</strong>.</small>
  </td>
  <td><input type="radio" name="productivity" value="0" {if $assessment->get_value("productivity")==0}CHECKED{/if}/>
    <small>Student's output is <strong>not yet acceptable</strong>.</small>
  </td>
</tr>

{* Quality of Work *}

<tr>
  <td class="property">Quality <br />{$assessment->flag_error("quality")}</td>
  <td><input type="radio" name="quality" value="6" {if $assessment->get_value("quality")==6}CHECKED{/if}/>
    <small>Student <strong>always</strong> produces work of the <strong>very highest quality</strong>.</small>
  </td>
  <td><input type="radio" name="quality" value="3" {if $assessment->get_value("quality")==3}CHECKED{/if}/>
    <small>Student <strong>frequently</strong> produces <strong>very good</strong> work.</small>
  </td>
  <td><input type="radio" name="quality" value="2" {if $assessment->get_value("quality")==2}CHECKED{/if}/>
    <small>Quality of student's work is <strong>acceptable</strong>.</small>
  </td>
  <td><input type="radio" name="quality" value="0" {if $assessment->get_value("quality")==0}CHECKED{/if}/>
    <small>Quality of student's work is <strong>not yet acceptable</strong>.</small>
  </td>
</tr>

{* Initiative *}

<tr>
  <td class="property" width="20%">Initiative <br />{$assessment->flag_error("initiative")}</td>
  <td width="20%"><input type="radio" name="initiative" value="4" {if $assessment->get_value("initiative")==4}CHECKED{/if}/>
    <small>Student takes initiative when <strong>appropriate</strong> and <strong>actively</strong> seeks opportunities to contribute to the department.</small>
  </td>
  <td width="20%"><input type="radio" name="initiative" value="3" {if $assessment->get_value("initiative")==3}CHECKED{/if}/>
    <small>Student takes initiative when <strong>appropriate</strong>.</small>
  </td>
  <td width="20%"><input type="radio" name="initiative" value="2" {if $assessment->get_value("initiative")==2}CHECKED{/if}/>
    <small>Student <strong>sometimes</strong> takes initiative.</small>
  </td>
  <td width="20%"><input type="radio" name="initiative" value="0" {if $assessment->get_value("initiative")==0}CHECKED{/if}/>
    <small>Student often <strong>avoids</strong> taking initiative.</small>
  </td>
</tr>

{* Enthusiasm *}

<tr>
  <td class="property">Enthusiasm <br />{$assessment->flag_error("enthusiasm")}</td>
  <td><input type="radio" name="enthusiasm" value="3" {if $assessment->get_value("enthusiasm")==3}CHECKED{/if}/>
    <small>Student is <strong>always very</strong> interested and enthusiastic.</small>
  </td>
  <td><input type="radio" name="enthusiasm" value="2" {if $assessment->get_value("enthusiasm")==2}CHECKED{/if}/>
    <small>Student is <strong>normally</strong> interested and enthusiastic.</small>
  </td>
  <td><input type="radio" name="enthusiasm" value="1" {if $assessment->get_value("enthusiasm")==1}CHECKED{/if}/>
    <small>Student is <strong>sometimes</strong> enthusiastic.</small>
  </td>
  <td><input type="radio" name="enthusiasm" value="0" {if $assessment->get_value("enthusiasm")==0}CHECKED{/if}/>
    <small>Student is <strong>rarely</strong> enthusiastic</small>
  </td>
</tr>

{* Interpersonal skills (including team working) *}

<tr>
  <td class="property">Interpersonal Skills (including team working) <br />{$assessment->flag_error("interpersonal")}</td>
  <td><input type="radio" name="interpersonal" value="4" {if $assessment->get_value("interpersonal")==4}CHECKED{/if}/>
    <small>Student <strong>always</strong> interacts effectively with colleagues / clients and shows respect for the views of others.</small>
  </td>
  <td><input type="radio" name="interpersonal" value="3" {if $assessment->get_value("interpersonal")==3}CHECKED{/if}/>
    <small>Student <strong>usually</strong> interacts effectively with colleagues / clients and shows respect for the views of others.</small>
  </td>
  <td><input type="radio" name="interpersonal" value="2" {if $assessment->get_value("interpersonal")==2}CHECKED{/if}/>
    <small>Student <strong>sometimes</strong> interacts effectively with colleagues / clients and shows respect for the views of others.</small>
  </td>
  <td><input type="radio" name="interpersonal" value="0" {if $assessment->get_value("interpersonal")==0}CHECKED{/if}/>
    <small>Student <strong>rarely</strong> interacts effectively with colleagues / clients.</small>
  </td>
  {* Never n/a *}
  <tr>
  </td>
</tr>

</table>
<table>
{*
<tr>
  <th>Category</th>
  <th>1</th>
  <th>0</th>
</tr>
*}

{* Attendance, Punctuality & Time Management *}

<tr>
  <td width="20%" class="property">Attendance, Punctuality &amp; Time Management <br />{$assessment->flag_error("attendance")}</td>
  <td width="40%"><input type="radio" name="attendance" value="2" {if $assessment->get_value("attendance")==2}CHECKED{/if}/>
    <small>Student attendance, punctuality and time management are <strong>acceptable</strong>.</small>
  </td>
  <td width="40%"><input type="radio" name="attendance" value="0" {if $assessment->get_value("attendance")==0}CHECKED{/if}/>
    <small>Student attendance, punctuality and time management are <strong>not yet acceptable</strong>.</small>
  </td>
  {* Never n/a *}
  <tr>
  </td>
</tr>

{* Log Book *}

<tr>
  <td class="property">Student Log Book <br />{$assessment->flag_error("log_book")}</td>
  <td><input type="radio" name="log_book" value="1" {if $assessment->get_value("log_book")==1}CHECKED{/if}/>
    <small>Student's log book has been <strong>regularly</strong> maintained to an <strong>appropriate</strong> level of detail.</small>
  </td>
  <td><input type="radio" name="log_book" value="0" {if $assessment->get_value("log_book")==0}CHECKED{/if}/>
    <small>Student's log book has not been regularly maintained, or is lacking in detail or was not available for inspection.</small>
  </td>
  {* Never n/a *}
  <tr>
  </td>
</tr>
</table>
</div>

{* Supervisor details *}
<h3>Supervisor Details</h3>
<p>If the supervisor details were not visible to you, please ask the student
to fill the details in without delay in the form on their home page.</p>

<h3>Health &amp; Safety</h3>
<p>Please check that the student has completed their health &amp; safety assessment
using the on-line form, and if not, ask them to complete it without delay.</p>
<div id="table_manage">
<table>
<tr>
  <td class="property">Health &amp; Safety<br />Matters Raised {$assessment->flag_error("hs_matters")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="hs_matters" rows="10" cols="60"}</td>
</tr>
<tr>
  <td class="property">Health &amp; Safety<br />Advice Given {$assessment->flag_error("hs_advice")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="hs_advice" rows="10" cols="60"}</td>
</tr>
</table>
</div>
<h3>Other Details</h3>
<div id="table_manage">
<table>
<tr>
  <td class="property">Perceived Strengths / Abilities {$assessment->flag_error("strengths")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="strengths" rows="10" cols="60"}</td>
</tr>
<tr>
  <td class="property">Perceived Weaknesses / Problems {$assessment->flag_error("weaknesses")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="weaknesses" rows="10" cols="60"}</td>
</tr>
<tr>
  <td class="property">Any Other Relevant Information {$assessment->flag_error("other")}</td>
  <td>{include file="general/assessment/textarea.tpl" name="other" rows="10" cols="60"}</td>
</tr>
<tr>
  <td class="property">Visit Grade</td>
  <td>
    <select name="visit_grade">
    <option value="15" {if $assessment->get_value("visit_grade")==15} selected{/if}>A+</option>
    <option value="14" {if $assessment->get_value("visit_grade")==14} selected{/if}>A</option>    
    <option value="13" {if $assessment->get_value("visit_grade")==13} selected{/if}>A-</option>
    <option value="12" {if $assessment->get_value("visit_grade")==12} selected{/if}>B+</option>    
    <option value="11" {if $assessment->get_value("visit_grade")==11} selected{/if}>B</option>
    <option value="10" {if $assessment->get_value("visit_grade")==10} selected{/if}>B-</option>    
    <option value="9" {if $assessment->get_value("visit_grade")==9} selected{/if}>C+</option>
    <option value="8" {if $assessment->get_value("visit_grade")==8} selected{/if}>C</option>    
    <option value="7" {if $assessment->get_value("visit_grade")==7} selected{/if}>C-</option>
    <option value="6" {if $assessment->get_value("visit_grade")==6} selected{/if}>D+</option>    
    <option value="5" {if $assessment->get_value("visit_grade")==5} selected{/if}>D</option>
    <option value="4" {if $assessment->get_value("visit_grade")==4} selected{/if}>D-</option>    
    <option value="3" {if $assessment->get_value("visit_grade")==3} selected{/if}>E+</option>    
    <option value="2" {if $assessment->get_value("visit_grade")==2} selected{/if}>E</option>
    <option value="1" {if $assessment->get_value("visit_grade")==1} selected{/if}>E-</option>    
    <option value="0" {if $assessment->get_value("visit_grade")==0} selected{/if}>F</option>        
    </select>
  </td>
<tr>
  <td class="property">Visit conducted</td>
  <td>
    <input type="radio" name="visit" value="On" {if $assessment->get_value("visit")} CHECKED {/if}> In Person
    <input type="radio" name="visit" value="" {if !$assessment->get_value("visit")} CHECKED {/if}> Remotely (e.g. by telephone)
  </td>
</tr>
<tr>
  <td class="property">Assessment Date<br />(DD/MM/YYYY) {$assessment->flag_error("visit_date")}</td>
  <td><input type="text" name="visit_date" value="{$assessment->get_value("visit_date")}" /></td>
</tr>  
</table>
<input type="SUBMIT" name="BUTTON" value="Submit">
</div>