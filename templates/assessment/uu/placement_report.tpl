{* Smarty *}
{* Template for SEME Final Placement Report *}

{* Assessment specific layout *}
{* Really, only the form contents need to be added *}
{* Note the use of get_value and flag_error to *}
{* bring in assessment specific material *}

<div id="table_manage">
<table cols="6">
<tr>
  <td colspan="6" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
{* Employer and Personal Work *}
<tr>
<td class="property" rowspan="5">Employer and Personal Work</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
{$assessment->flag_error("mark1")}
<input type="text" class="data_entry_required" size="2" value="{$assessment->get_value("mark1")}" name="mark1">
</td>
<td colspan="4">
{$assessment->flag_error("comment1")}
<textarea rows="5" cols="30" name="comment1">{$assessment->get_value("comment1")|escape:"htmlall"}</textarea></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td><small>Full description, markets, organisation, products / services, IR, personal work</small></td>
<td><small>Covers most points, short or long</small></td>
<td><small>States the obvious, lacks investigation and detail</small></td>
<td><small>Brief mention of a few obvious facts, vague on work</small></td>
<td><small>Very brief, does know the organization, shallow on work description</small></td>
</tr>
<tr>
<td ALIGN="CENTER">20 .. 18</td>
<td ALIGN="CENTER">16 .. 14</td>
<td ALIGN="CENTER">12 .. 10</td>
<td ALIGN="CENTER">08 .. 05</td>
<td ALIGN="CENTER">04 .. 02</td>
</tr>
  
{* Innovation in the Organisation *}

<tr>
<td class="property" rowspan="5">Innovation in the Organisation</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
{$assessment->flag_error("mark2")}
<input type="text" class="data_entry_required" size="2" value="{$assessment->get_value("mark2")}" name="mark2">
</td>
<td colspan="4">
{$assessment->flag_error("comment2")}
<textarea rows="5" cols="30" name="comment2">{$assessment->get_value("comment2")|escape:"htmlall"}</textarea></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td><small>Understands innovation, its impact, perceptive</small></td>
<td><small>Has a grasp of the topic, good comment on organisation profile</small></td>
<td><small>Shows some understanding, a few points well made</small></td>
<td><small>Limited grasp, few examples, unclear on organisational profile</small></td>
<td><small>Has not grasped the issues, limited view on organisation profile</small></td>
</tr>
<tr>
<td ALIGN="CENTER">20 .. 18</td>
<td ALIGN="CENTER">16 .. 14</td>
<td ALIGN="CENTER">12 .. 10</td>
<td ALIGN="CENTER">08 .. 05</td>
<td ALIGN="CENTER">04 .. 02</td>
</tr>

{* Reflection on Benefit of Placement *}

<tr>
<td class="property" rowspan="5">Reflection on Benefit of Placement</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark3")}
<input type="text" class="data_entry_required" size="2" value="{$assessment->get_value("mark3")}" name="mark3">
</td>
<td colspan="4">
  {$assessment->flag_error("comment3")}
<textarea rows="5" cols="30" name="comment3">{$assessment->get_value("comment3")|escape:"htmlall"}</textarea></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td><small>Rational, extensive, wide range of skills, perceptive, valuable comment</small></td>
<td><small>Outlines most skill areas adequately, credible comments on placement</small></td>
<td><small>A few skills identified and explained, lacks reflection</small></td>
<td><small>Limited benefit, few skills identified, brief comment</small></td>
<td><small>Minor comment, no benefit, lacks application, no suggestions</small></td>
</tr>
<tr>
<td ALIGN="CENTER">30 .. 25</td>
<td ALIGN="CENTER">24 .. 19</td>
<td ALIGN="CENTER">18 .. 13</td>
<td ALIGN="CENTER">12 .. 07</td>
<td ALIGN="CENTER">06 .. 0</td>
</tr>


{* Spelling and punctuation row *}

<tr>
<td class="property" rowspan="5">Spelling and Punctuation</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark4")}
<input type="text" class="data_entry_required" size="2" value="{$assessment->get_value("mark4")}" name="mark4">
</td>
<td colspan="4">
  {$assessment->flag_error("comment4")}
<textarea rows="5" cols="30" name="comment4">{$assessment->get_value("comment4")|escape:"htmlall"}</textarea></td>
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
<input type="text" class="data_entry_required" size="2" value="{$assessment->get_value("mark5")}" name="mark5">
</td>
<td colspan="4">
  {$assessment->flag_error("comment5")}
<textarea rows="5" cols="30" name="comment5">{$assessment->get_value("comment5")|escape:"htmlall"}</textarea></td>
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

{* Presentation *}

<tr>
<td class="property" rowspan="5">Structure of Ideas</td>
<td colspan="1"><strong>Mark</strong></td><td colspan="4"><strong>Comments</strong></td></tr>

<tr><td colspan="1">
  {$assessment->flag_error("mark6")}
<input type="text" class="data_entry_required" size="2" value="{$assessment->get_value("mark6")}" name="mark6">
</td>
<td colspan="4">
  {$assessment->flag_error("comment6")}
<textarea rows="5" cols="30" name="comment6">{$assessment->get_value("comment6")|escape:"htmlall"}</textarea></td>
</tr>

<tr><td colspan="5">Marking Scheme</td></tr>
<tr>
<td><small> Follows the specification completely, within limits set </small></td>
<td><small> Generally follows the specification but with some deviation </small></td>
<td><small> Introduces minor personal style to the detriment of the specification </small></td>
<td><small> Pays scant regard to specification, personal style dominant </small></td>
<td><small> Unattractive or overdone to detriment, careless </small></td>
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
  <td colspan="5">
  <textarea name="comments" rows="15" cols="70">{$assessment->get_value("comments")|escape:"htmlall"}</textarea>
  </td>
</tr>
<tr>
  <td colspan="6" class="button"><input type="submit" class="submit" value="confirm" /></td>
</tr>
</table>
</div>