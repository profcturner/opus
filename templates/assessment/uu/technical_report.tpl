{* Smarty *}
{* Template for SEME Technical Report *}


{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}

<div id="table_manage">
<table>
<tr>
  <td colspan="6" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
{* Practice / Process *}
<tr>
<td class="property" rowspan="5">Description of Practice/Process</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
{$assessment->flag_error("mark1")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->get_value("mark1")}" NAME="mark1">
</td>
<td colspan="4">
{$assessment->flag_error("comment1")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment1">{$assessment->get_value("comment1")|escape:"htmlall"}</TEXTAREA></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td colspan="2">Full logical description of technical process</td>
<td colspan="1">Outline description, some details</td>
<td colspan="2">Very brief outline, incomplete, missing details</td>
</tr>
<tr>
<td ALIGN="CENTER">20 .. 18</td>
<td ALIGN="CENTER">16 .. 14</td>
<td ALIGN="CENTER">12 .. 10</td>
<td ALIGN="CENTER">08 .. 05</td>
<td ALIGN="CENTER">04 .. 02</td>
</tr>

{* Principles / Theory *}

<tr>
<td class="property" rowspan="5">Understanding of Principles/Theory</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
{$assessment->flag_error("mark2")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->get_value("mark2")}" NAME="mark2">
</td>
<td colspan="4">
{$assessment->flag_error("comment2")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment2">{$assessment->get_value("comment2")|escape:"htmlall"}</TEXTAREA></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td colspan="2"> Clear grasp of relevant underlying principles </td>
<td colspan="1"> Some understanding of principles </td>
<td colspan="2"> No evidence of understanding </td>
</tr>
<tr>
<td ALIGN="CENTER">20 .. 18</td>
<td ALIGN="CENTER">16 .. 14</td>
<td ALIGN="CENTER">12 .. 10</td>
<td ALIGN="CENTER">08 .. 05</td>
<td ALIGN="CENTER">04 .. 02</td>
</tr>

{* Invesigation/Research Row *}

<tr>
<td class="property" rowspan="5">Investigation/Research</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark3")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->get_value("mark3")}" NAME="mark3">
</td>
<td colspan="4">
  {$assessment->flag_error("comment3")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment3">{$assessment->get_value("comment3")|escape:"htmlall"}</TEXTAREA></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td colspan="2"> Thorough investigation with references cited </td>
<td colspan="1"> Some evidence of investigation </td>
<td colspan="2"> No evidence of investigation </td>
</tr>
<tr>
<td ALIGN="CENTER">20 .. 18</td>
<td ALIGN="CENTER">16 .. 14</td>
<td ALIGN="CENTER">12 .. 10</td>
<td ALIGN="CENTER">08 .. 05</td>
<td ALIGN="CENTER">04 .. 02</td>
</tr>


{* Spelling and punctuation row........ *}

<tr>
<td class="property" rowspan="5">Spelling and Punctuation</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark4")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->get_value("mark4")}" NAME="mark4">
</td>
<td colspan="4">
  {$assessment->flag_error("comment4")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment4">{$assessment->get_value("comment4")|escape:"htmlall"}</TEXTAREA></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td>No errors</td>
<td>Very few minor errors</td>
<td>Some errors indicating some carelessness</td>
<td>Careless errors</td>
<td>Significant level of error</td>
</tr>
<tr>
<td ALIGN="CENTER">10 .. 09</td>
<td ALIGN="CENTER">08 .. 07</td>
<td ALIGN="CENTER">06 .. 05</td>
<td ALIGN="CENTER">04 .. 03</td>
<td ALIGN="CENTER">02 .. 01</td>
</tr>
{* Use of english row.... *}

<tr>
<td class="property" rowspan="5">Use of English</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark5")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->get_value("mark5")}" NAME="mark5">
</td>
<td colspan="4">
  {$assessment->flag_error("comment5")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment5">{$assessment->get_value("comment5")|escape:"htmlall"}</TEXTAREA></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td> Uses language to clearly express views concisely </td>
<td> Expresses clearly but with some minor errors </td>
<td> Good expression, logical flow, reasonably concise </td>
<td> Reasonable flow, some contorted expressions, a little verbose </td>
<td> Poor expression, verbose, some colloquialisms </td>
</tr>
<tr>
<td ALIGN="CENTER">10 .. 09</td>
<td ALIGN="CENTER">08 .. 07</td>
<td ALIGN="CENTER">06 .. 05</td>
<td ALIGN="CENTER">04 .. 03</td>
<td ALIGN="CENTER">02 .. 01</td>
</tr>

{* Structure of ideas row... *}

<tr>
<td class="property" rowspan="5">Structure of Ideas</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark6")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->get_value("mark6")}" NAME="mark6">
</td>
<td colspan="4">
  {$assessment->flag_error("comment6")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment6">{$assessment->get_value("comment6")|escape:"htmlall"}</TEXTAREA></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td> Clear logical development </td>
<td> Follows the general flow of ideas </td>
<td> Overal flow but some convoluted thinking </td>
<td> Logical main theme but significant local confusion </td>
<td> Ideas jumbled with evident structure </td>
</tr>
<tr>
<td ALIGN="CENTER">10 .. 09</td>
<td ALIGN="CENTER">08 .. 07</td>
<td ALIGN="CENTER">06 .. 05</td>
<td ALIGN="CENTER">04 .. 03</td>
<td ALIGN="CENTER">02 .. 01</td>
</tr>

{* Final row of the table *}

<tr>
<td class="property" rowspan="5">Presentation</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark7")}
<INPUT type="text" class="data_entry_required" SIZE="2" VALUE="{$assessment->get_value("mark7")}" NAME="mark7">
</td>
<td colspan="4">
  {$assessment->flag_error("comment7")}
<TEXTAREA ROWS="5" COLS="30" NAME="comment7">{$assessment->get_value("comment7")|escape:"htmlall"}</TEXTAREA></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td> Follows the specification completly, within limits set </td>
<td>Generally follows the specification but with some deviation </td>
<td> Introduces minor personal style to detriment of the specification </td>
<td> Pays scant regards to specification, personal style dominant </td>
<td> Unattractive or overdone to detriment, careless </td>
</tr>
<tr>
<td ALIGN="CENTER">10 .. 09</td>
<td ALIGN="CENTER">08 .. 07</td>
<td ALIGN="CENTER">06 .. 05</td>
<td ALIGN="CENTER">04 .. 03</td>
<td ALIGN="CENTER">02 .. 01</td>
</tr>

<tr>
  <td class="property">General Comments</td>
  <td colspan="5"><textarea name="comments" rows="15" cols="70">{$assessment->get_value("comments")|escape:"htmlall"}</textarea></td>
</tr>
<tr>
  <td colspan="6" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
</table>
</div>
