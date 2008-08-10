{* Smarty *}
{* Template for SEME Presentation *}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}

<div id="table_manage">
<table cols="7">

<tr>
  <td colspan="7" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
<tr><TH>Criteria</TH><TH colspan="2">Meaning of Marks</TH><TH>A</TH><TH>B</TH><TH>C</TH><TH>D</TH></tr>
<tr>
  <td rowspan="3" class="property">Content</td>
  <td width="30">5</td>
  <td><small>Comprehensive, relevant, balanced criticism, pitched at appropriate level</small></td>
  <td rowspan="3" width="50">{$assessment->flag_error("ContentA")}
    <input type="text" size="2" name="ContentA" value="{$assessment->get_value("ContentA")}"></td>
  <td rowspan="3" width="50">
  {$assessment->flag_error("ContentB")}
  <input type="text" size="2" name="ContentB" value="{$assessment->get_value("ContentA")}"></td>
  <td rowspan="3" width="50">
  {$assessment->flag_error("ContentC")}
  <input type="text" size="2" name="ContentC" value="{$assessment->get_value("ContentA")}"></td>
  <td rowspan="3" width="50">
  {$assessment->flag_error("ContentD")}
  <input type="text" size="2" name="ContentD" value="{$assessment->get_value("ContentA")}"></td>
</tr>
<tr><td width="30">3</td><td><small>Generally describes the experience, offers some suggestions</small></td></tr>
<tr><td width="30">1</td><td><small>Does not address the issues of placement, single issue, lacks perception</small></td></tr>

<tr><td class="property" rowspan="3" width="100">Structure</td><td width="30">5</td>
<td><small>Thoroughly planned, logical development, good use of time</small></td>
<TD rowspan="3">
  {$assessment->flag_error("StructureA")}
<input type="text" size="2" name="StructureA" value="{$assessment->get_value("StructureA")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("StructureB")}
<input type="text" size="2" name="StructureB" value="{$assessment->get_value("StructureB")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("StructureC")}
<input type="text" size="2" name="StructureC" value="{$assessment->get_value("StructureC")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("StructureD")}
<input type="text" size="2" name="StructureD" value="{$assessment->get_value("StructureD")}"></td>
</tr>
<tr><TD width="30">3</td><td><small>Partly stuctured, mostly logical, inappropriate use of time</small></td></tr>
<tr><TD width="30">1</td><td><small>Cuffing, no structure, under/over use of time</small></td></tr>


<tr><td class="property" rowspan="3" width="100">Visuals</td><td width="30">5</td>
<td><small>Adequate number, good timing, clear, neat, relevant, supportive</small></td>
<TD rowspan="3">
  {$assessment->flag_error("VisualsA")}
<input type="text" size="2" name="VisualsA" value="{$assessment->get_value("VisualsA")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("VisualsB")}
<input type="text" size="2" name="VisualsB" value="{$assessment->get_value("VisualsB")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("VisualsC")}
<input type="text" size="2" name="VisualsC" value="{$assessment->get_value("VisualsC")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("VisualsD")}
<input type="text" size="2" name="VisualsD" value="{$assessment->get_value("VisualsD")}"></td>
</tr>
<tr><TD width="30">3</td><td><small>Visuals aids used to assist, but with distractions, difficult to see/read</small></td></tr>
<tr><TD width="30">1</td><td><small>No visual aids where several would have been useful, inadequate</small></td></tr>

<tr><td class="property" rowspan="3" width="100">Delivery</td><td width="30">5</td>
<td><small>Audible, fluent, confident, correct use of language, limited use of notes</small></td>
<TD rowspan="3">
  {$assessment->flag_error("DeliveryA")}
<input type="text" size="2" name="DeliveryA" value="{$assessment->get_value("DeliveryA")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("DeliveryB")}
<input type="text" size="2" name="DeliveryB" value="{$assessment->get_value("DeliveryB")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("DeliveryC")}
<input type="text" size="2" name="DeliveryC" value="{$assessment->get_value("DeliveryC")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("DeliveryD")}
<input type="text" size="2" name="DeliveryD" value="{$assessment->get_value("DeliveryD")}"></td>
</tr>
<tr><TD width="30">3</td><td><small>Audible, partly convincing, constant reliance on notes, a few mannerisms</small></td></tr>
<tr><TD width="30">1</td><td><small>Inaudible, lacking confidence, bad stance, read notes or OHP</small></td></tr>

<tr><td class="property" rowspan="3" width="100">Questions</td><td width="30">5</td>
<td><small>Answers the question completely and convincingly from evident knowledge</small></td>
<TD rowspan="3">
  {$assessment->flag_error("QuestionsA")}
<input type="text" size="2" name="QuestionsA" value="{$assessment->get_value("QuestionsA")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("QuestionsB")}
<input type="text" size="2" name="QuestionsB" value="{$assessment->get_value("QuestionsB")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("QuestionsC")}
<input type="text" size="2" name="QuestionsC" value="{$assessment->get_value("QuestionsC")}"></td>
<TD rowspan="3">
  {$assessment->flag_error("QuestionsD")}
<input type="text" size="2" name="QuestionsD" value="{$assessment->get_value("QuestionsD")}"></td>
</tr>
<tr><TD width="30">3</td><td><small>Answers most questions and appears convincing, minor gaps in reply</small></td></tr>
<tr><TD width="30">1</td><td><small>Cannot answer the question, answers another question, bluffing</small></td></tr>

<tr>
  <td class="property">Notes<br /><small>(if required)</small></td>
  <td colspan="6">{$assessment->flag_error("Notes")}
  {include file="general/assessment/textarea.tpl" name="Notes" rows="10" cols="60"}</td>
</tr>
<tr>
  <td colspan="7" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>

</table>
</div>