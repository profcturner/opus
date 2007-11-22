{* Smarty *}
{* Template for SEME First Visit *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}


<TABLE ALIGN="CENTER" BORDER="1">
<TR><TH>Checklist</TH><TH>Yes / No</TH><TH>Comments</TH></TR>
<TR><TH>Training / Experience programme arranged</TH>
<TD><INPUT TYPE="CHECKBOX" NAME="check1"
{if $assessment->getValue("check1")} CHECKED {/if}
></TD>
<TD>{$assessment->flagVariable("comment1")}
<TEXTAREA ROWS="3" COLS="60" NAME="comment1">
{$assessment->getValue("comment1")|escape:"htmlall"}
</TEXTAREA></TR>

<TR><TH>Industrial Supervisor Appointed</TH>
<TD><INPUT TYPE="CHECKBOX" NAME="check2"
{if $assessment->getValue("check2")} CHECKED {/if}
></TD>
<TD>{$assessment->flagVariable("comment2")}
<TEXTAREA ROWS="3" COLS="60" NAME="comment2">
{$assessment->getValue("comment2")|escape:"htmlall"}
</TEXTAREA></TR>

<TR><TH>Student Interviewed</TH>
<TD><INPUT TYPE="CHECKBOX" NAME="check3"
{if $assessment->getValue("check3")} CHECKED {/if}
></TD>
<TD>{$assessment->flagVariable("comment3")}
<TEXTAREA ROWS="3" COLS="60" NAME="comment3">
{$assessment->getValue("comment3")|escape:"htmlall"}
</TEXTAREA></TR>

<TR><TH>Company Representative Interviewed</TH>
<TD><INPUT TYPE="CHECKBOX" NAME="check4"
{if $assessment->getValue("check4")} CHECKED {/if}
></TD>
<TD>{$assessment->flagVariable("comment4")}
<TEXTAREA ROWS="3" COLS="60" NAME="comment4">
{$assessment->getValue("comment4")|escape:"htmlall"}
</TEXTAREA></TR>

<TR><TH>Log Book Inspected</TH>
<TD><INPUT TYPE="CHECKBOX" NAME="check5"
{if $assessment->getValue("check5")} CHECKED {/if}
></TD><TD>
  {$assessment->flagVariable("comment5")}
<TEXTAREA ROWS="3" COLS="60" NAME="comment5">
{$assessment->getValue("comment5")|escape:"htmlall"}
</TEXTAREA></TR>

<TR><TH>Health and Safety Checklist Inspected</TH>
<TD><INPUT TYPE="CHECKBOX" NAME="check6"
{if $assessment->getValue("check6")} CHECKED {/if}
></TD><TD>
  {$assessment->flagVariable("comment6")}
<TEXTAREA ROWS="3" COLS="60" NAME="comment6">
{$assessment->getValue("comment6")|escape:"htmlall"}
</TEXTAREA></TR>

<TR><TH>Student Accomodation Satisfactory</TH>
<TD><INPUT TYPE="CHECKBOX" NAME="check7"
{if $assessment->getValue("check7")} CHECKED {/if}
></TD><TD>
  {$assessment->flagVariable("comment7")}
<TEXTAREA ROWS="3" COLS="60" NAME="comment7">
{$assessment->getValue("comment7")|escape:"htmlall"}
</TEXTAREA></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Changes to the Training / Experience Programme</TH></TR>
<TR><TD COLSPAN="3">
  {$assessment->flagVariable("changes")}
<TEXTAREA COLS="80" ROWS="10" NAME="changes">
{$assessment->getValue("changes")|escape:"htmlall"}
</TEXTAREA></TD></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Comments on the Student and Programme</TH></TR>
<TR><TD COLSPAN="3">
  {$assessment->flagVariable("scomments")}
<TEXTAREA COLS="80" ROWS="10" NAME="scomments">
{$assessment->getValue("scomments")|escape:"htmlall"}
</TEXTAREA></TD></TR>

<TR><TH COLSPAN="3" ALIGN="CENTER">Advice given to the Student</TH></TR>
<TR><TD COLSPAN="3">
  {$assessment->flagVariable("advice")}
<TEXTAREA COLS="80" ROWS="10" NAME="advice">
{$assessment->getValue("advice")|escape:"htmlall"}
</TEXTAREA></TD></TR>

<TR><TD COLSPAN="3" ALIGN="CENTER">Assessment date (DD/MM/YYYY)<BR>
  {$assessment->flagVariable("assesseddate")}
<INPUT TYPE="TEXT" NAME="assesseddate" VALUE="{$assessment->getValue("assesseddate")}"><BR>
<INPUT TYPE="SUBMIT" NAME="BUTTON" VALUE="Submit"></TD></TR>

</TABLE>
  



{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}
