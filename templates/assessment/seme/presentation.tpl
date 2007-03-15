{* Smarty *}
{* Template for SEME Presentation *}

{* Standard Header for assessments *}
{include file="assessment/assessment_header.tpl"}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of getValue and flagVariable to *}
{* bring in assessment specific material *}

<TABLE $width ALIGN="CENTER" COLS="3" BORDER="1">

<TR><TH>Criteria</TH><TH COLSPAN="2">Meaning of Marks</TH><TH>A</TH><TH>B</TH><TH>C</TH><TH>D</TH></TR>
<TR><TH ROWSPAN="3" WIDTH="100">Content</TH><TD WIDTH="30">5</TD>
<TD><SMALL>Comprehensive, relevant, balanced criticism, pitched at appropriate level</SMALL></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("ContentA")}
<INPUT TYPE="TEXT" SIZE="2" NAME="ContentA" VALUE="{$assessment->getValue("ContentA")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("ContentB")}
<INPUT TYPE="TEXT" SIZE="2" NAME="ContentB" VALUE="{$assessment->getValue("ContentA")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("ContentC")}
<INPUT TYPE="TEXT" SIZE="2" NAME="ContentC" VALUE="{$assessment->getValue("ContentA")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("ContentD")}
<INPUT TYPE="TEXT" SIZE="2" NAME="ContentD" VALUE="{$assessment->getValue("ContentA")}"></TD>
</TR>
<TR><TD WIDTH="30">3</TD><TD><SMALL>Generally describes the experience, offers some suggestions</SMALL></TD></TR>
<TR><TD WIDTH="30">1</TD><TD><SMALL>Does not address the issues of placement, single issue, lacks perception</SMALL></TD></TR>

<TR><TH ROWSPAN="3" WIDTH="100">Structure</TH><TD WIDTH="30">5</TD>
<TD><SMALL>Thoroughly planned, logical development, good use of time</SMALL></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("StructureA")}
<INPUT TYPE="TEXT" SIZE="2" NAME="StructureA" VALUE="{$assessment->getValue("StructureA")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("StructureB")}
<INPUT TYPE="TEXT" SIZE="2" NAME="StructureB" VALUE="{$assessment->getValue("StructureB")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("StructureC")}
<INPUT TYPE="TEXT" SIZE="2" NAME="StructureC" VALUE="{$assessment->getValue("StructureC")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("StructureD")}
<INPUT TYPE="TEXT" SIZE="2" NAME="StructureD" VALUE="{$assessment->getValue("StructureD")}"></TD>
</TR>
<TR><TD WIDTH="30">3</TD><TD><SMALL>Partly stuctured, mostly logical, inappropriate use of time</SMALL></TD></TR>
<TR><TD WIDTH="30">1</TD><TD><SMALL>Cuffing, no structure, under/over use of time</SMALL></TD></TR>


<TR><TH ROWSPAN="3" WIDTH="100">Visuals</TH><TD WIDTH="30">5</TD>
<TD><SMALL>Adequate number, good timing, clear, neat, relevant, supportive</SMALL></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("VisualsA")}
<INPUT TYPE="TEXT" SIZE="2" NAME="VisualsA" VALUE="{$assessment->getValue("VisualsA")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("VisualsB")}
<INPUT TYPE="TEXT" SIZE="2" NAME="VisualsB" VALUE="{$assessment->getValue("VisualsB")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("VisualsC")}
<INPUT TYPE="TEXT" SIZE="2" NAME="VisualsC" VALUE="{$assessment->getValue("VisualsC")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("VisualsD")}
<INPUT TYPE="TEXT" SIZE="2" NAME="VisualsD" VALUE="{$assessment->getValue("VisualsD")}"></TD>
</TR>
<TR><TD WIDTH="30">3</TD><TD><SMALL>Visuals aids used to assist, but with distractions, difficult to see/read</SMALL></TD></TR>
<TR><TD WIDTH="30">1</TD><TD><SMALL>No visual aids where several would have been useful, inadequate</SMALL></TD></TR>

<TR><TH ROWSPAN="3" WIDTH="100">Delivery</TH><TD WIDTH="30">5</TD>
<TD><SMALL>Audible, fluent, confident, correct use of language, limited use of notes</SMALL></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("DeliveryA")}
<INPUT TYPE="TEXT" SIZE="2" NAME="DeliveryA" VALUE="{$assessment->getValue("DeliveryA")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("DeliveryB")}
<INPUT TYPE="TEXT" SIZE="2" NAME="DeliveryB" VALUE="{$assessment->getValue("DeliveryB")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("DeliveryC")}
<INPUT TYPE="TEXT" SIZE="2" NAME="DeliveryC" VALUE="{$assessment->getValue("DeliveryC")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("DeliveryD")}
<INPUT TYPE="TEXT" SIZE="2" NAME="DeliveryD" VALUE="{$assessment->getValue("DeliveryD")}"></TD>
</TR>
<TR><TD WIDTH="30">3</TD><TD><SMALL>Audible, partly convincing, constant reliance on notes, a few mannerisms</SMALL></TD></TR>
<TR><TD WIDTH="30">1</TD><TD><SMALL>Inaudible, lacking confidence, bad stance, read notes or OHP</SMALL></TD></TR>

<TR><TH ROWSPAN="3" WIDTH="100">Questions</TH><TD WIDTH="30">5</TD>
<TD><SMALL>Answers the question completely and convincingly from evident knowledge</SMALL></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("QuestionsA")}
<INPUT TYPE="TEXT" SIZE="2" NAME="QuestionsA" VALUE="{$assessment->getValue("QuestionsA")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("QuestionsB")}
<INPUT TYPE="TEXT" SIZE="2" NAME="QuestionsB" VALUE="{$assessment->getValue("QuestionsB")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("QuestionsC")}
<INPUT TYPE="TEXT" SIZE="2" NAME="QuestionsC" VALUE="{$assessment->getValue("QuestionsC")}"></TD>
<TD ROWSPAN="3">
  {$assessment->flagVariable("QuestionsD")}
<INPUT TYPE="TEXT" SIZE="2" NAME="QuestionsD" VALUE="{$assessment->getValue("QuestionsD")}"></TD>
</TR>
<TR><TD WIDTH="30">3</TD><TD><SMALL>Answers most questions and appears convincing, minor gaps in reply</SMALL></TD></TR>
<TR><TD WIDTH="30">1</TD><TD><SMALL>Cannot answer the question, answers another question, bluffing</SMALL></TD></TR>

<TR><TD COLSPAN="7" ALIGN="CENTER">Any Notes (if required)</TD></TR>
<TR><TD COLSPAN="7" ALIGN="CENTER">
  {$assessment->flagVariable("Notes")}
<TEXTAREA COLS="60" ROWS="10" NAME="Notes">{$assessment->getValue("Notes")|escape:"htmlall"}</TEXTAREA></TD></TR>

<TR><TD COLSPAN="7" ALIGN="CENTER">
<INPUT TYPE="SUBMIT" NAME="BUTTON" VALUE="Submit"></TD></TR>

</TABLE>

  

{* Standard footer for assessments *}
{include file="assessment/assessment_footer.tpl"}
