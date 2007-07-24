{* Smarty *}
{* Template for SEME Technical Report *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}

<TABLE WIDTH="700" COLS="6" ALIGN="CENTER" BORDER="1">

{* Practice / Process *}
<TR>
<TH ROWSPAN="5">Description of Practice/Process</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
{$assessment->flagVariable("mark1")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark1")}" NAME="mark1">
</TD>
<TD COLSPAN="4">
{$assessment->flagVariable("comment1")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment1">{$assessment->getValue("comment1")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD COLSPAN="2">Full logical description of technical process</TD>
<TD COLSPAN="1">Outline description, some details</TD>
<TD COLSPAN="2">Very brief outline, incomplete, missing details</TD>
</TR>
<TR>
<TD ALIGN="CENTER">20 .. 18</TD>
<TD ALIGN="CENTER">16 .. 14</TD>
<TD ALIGN="CENTER">12 .. 10</TD>
<TD ALIGN="CENTER">08 .. 05</TD>
<TD ALIGN="CENTER">04 .. 02</TD>
</TR>
  
{* Principles / Theory *}

<TR>
<TH ROWSPAN="5">Understanding of Principles/Theory</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
{$assessment->flagVariable("mark2")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark2")}" NAME="mark2">
</TD>
<TD COLSPAN="4">
{$assessment->flagVariable("comment2")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment2">{$assessment->getValue("comment2")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD COLSPAN="2"> Clear grasp of relevant underlying principles </TD>
<TD COLSPAN="1"> Some understanding of principles </TD>
<TD COLSPAN="2"> No evidence of understanding </TD>
</TR>
<TR>
<TD ALIGN="CENTER">20 .. 18</TD>
<TD ALIGN="CENTER">16 .. 14</TD>
<TD ALIGN="CENTER">12 .. 10</TD>
<TD ALIGN="CENTER">08 .. 05</TD>
<TD ALIGN="CENTER">04 .. 02</TD>
</TR>

{* Invesigation/Research Row *}

<TR>
<TH ROWSPAN="5">Investigation/Research</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark3")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark3")}" NAME="mark3">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment3")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment3">{$assessment->getValue("comment3")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD COLSPAN="2"> Thorough investigation with references cited </TD>
<TD COLSPAN="1"> Some evidence of investigation </TD>
<TD COLSPAN="2"> No evidence of investigation </TD>
</TR>
<TR>
<TD ALIGN="CENTER">20 .. 18</TD>
<TD ALIGN="CENTER">16 .. 14</TD>
<TD ALIGN="CENTER">12 .. 10</TD>
<TD ALIGN="CENTER">08 .. 05</TD>
<TD ALIGN="CENTER">04 .. 02</TD>
</TR>


{* Spelling and punctuation row........ *}

<TR>
<TH ROWSPAN="5">Spelling and Punctuation</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark4")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark4")}" NAME="mark4">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment4")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment4">{$assessment->getValue("comment4")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD>No errors</TD>
<TD>Very few minor errors</TD>
<TD>Some errors indicating some carelessness</TD>
<TD>Careless errors</TD>
<TD>Significant level of error</TD>
</TR>
<TR>
<TD ALIGN="CENTER">10 .. 09</TD>
<TD ALIGN="CENTER">08 .. 07</TD>
<TD ALIGN="CENTER">06 .. 05</TD>
<TD ALIGN="CENTER">04 .. 03</TD>
<TD ALIGN="CENTER">02 .. 01</TD>
</TR>
{* Use of english row.... *}

<TR>
<TH ROWSPAN="5">Use of English</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark5")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark5")}" NAME="mark5">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment5")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment5">{$assessment->getValue("comment5")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD> Uses language to clearly express views concisely </TD>
<TD> Expresses clearly but with some minor errors </TD>
<TD> Good expression, logical flow, reasonably concise </TD>
<TD> Reasonable flow, some contorted expressions, a little verbose </TD>
<TD> Poor expression, verbose, some colloquialisms </TD>
</TR>
<TR>
<TD ALIGN="CENTER">10 .. 09</TD>
<TD ALIGN="CENTER">08 .. 07</TD>
<TD ALIGN="CENTER">06 .. 05</TD>
<TD ALIGN="CENTER">04 .. 03</TD>
<TD ALIGN="CENTER">02 .. 01</TD>
</TR>

{* Structure of ideas row... *}

<TR>
<TH ROWSPAN="5">Structure of Ideas</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark6")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark6")}" NAME="mark6">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment6")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment6">{$assessment->getValue("comment6")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD> Clear logical development </TD>
<TD> Follows the general flow of ideas </TD>
<TD> Overal flow but some convoluted thinking </TD>
<TD> Logical main theme but significant local confusion </TD>
<TD> Ideas jumbled with evident structure </TD>
</TR>
<TR>
<TD ALIGN="CENTER">10 .. 09</TD>
<TD ALIGN="CENTER">08 .. 07</TD>
<TD ALIGN="CENTER">06 .. 05</TD>
<TD ALIGN="CENTER">04 .. 03</TD>
<TD ALIGN="CENTER">02 .. 01</TD>
</TR>

{* Final row of the table *}

<TR>
<TH ROWSPAN="5">Presentation</TH>
<TD COLSPAN="1"><B>Mark</B></TD><TD COLSPAN="4"><B>Comments</B></TD></TR>

<TR><TD COLSPAN="1">
  {$assessment->flagVariable("mark7")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->getValue("mark7")}" NAME="mark7">
</TD>
<TD COLSPAN="4">
  {$assessment->flagVariable("comment7")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment7">{$assessment->getValue("comment7")|escape:"htmlall"}</TEXTAREA></TD>
</TR>

<TR><TD COLSPAN="5">Marking Scheme</TD></TR>
<TR>
<TD> Follows the specification completly, within limits set </TD>
<TD>Generally follows the specification but with some deviation </TD>
<TD> Introduces minor personal style to detriment of the specification </TD>
<TD> Pays scant regards to specification, personal style dominant </TD>
<TD> Unattractive or overdone to detriment, careless </TD>
</TR>
<TR>
<TD ALIGN="CENTER">10 .. 09</TD>
<TD ALIGN="CENTER">08 .. 07</TD>
<TD ALIGN="CENTER">06 .. 05</TD>
<TD ALIGN="CENTER">04 .. 03</TD>
<TD ALIGN="CENTER">02 .. 01</TD>
</TR>

<TR><TD ALIGN="CENTER" COLSPAN="6">
<B>General Comments</B><BR>
<TEXTAREA NAME="comments" ROWS="15" COLS="70">{$assessment->getValue("comments")|escape:"htmlall"}</TEXTAREA><BR>
<INPUT TYPE="SUBMIT" NAME="BUTTON" VALUE="Submit"></TD></TR>

</TABLE>
  

{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}
