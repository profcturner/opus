<div id="table_list">
{eval assign=object_num var=$objects|@count}
{if $object_num > #TABLE_ROW_MAX#}
{math assign=pages equation="ceil((x/y)+1)" x=$object_num y=#TABLE_ROW_MAX#}
{math assign=start_obj equation="(p-1)*n" n=#TABLE_ROW_MAX# p=$page|default:1}
pages
{section name="myLoop" start=1 loop=$pages}
{if $page==$smarty.section.myLoop.index}<strong>{$smarty.section.myLoop.index}</strong>&nbsp;{else}<a href="{$SCRIPT_NAME}?function={$smarty.request.function}&page={$smarty.section.myLoop.index}">{$smarty.section.myLoop.index}</a>&nbsp;{/if}
{/section}
{/if}
{section loop=$objects name=object start=$start_obj max=#TABLE_ROW_MAX#}
  <div class="{cycle values="dark_row,light_row"} fancy_table">
    <div class="qualification_subjects">{$objects[object]->subjects}</div>
    <div class="qualification_qualification">({$objects[object]->qualification})</div>
    <div class="qualification_grade_mark"> {$objects[object]->grade_mark} </div>
    <div class="qualification_date_attained">{$objects[object]->date_attained}</div>
    <div class="qualification_institution">{$objects[object]->institution}</div>
    <div class="qualification_awarding_body">({$objects[object]->awarding_body})</div>
    <div class="skills_developed_heading">Skills Developed</div>
    <div class="qualification_skills_developed">{$objects[object]->skills_developed}</div>
  </div>
{section loop=$actions name=action}
    
      <a href="{#APPLICATION_URL#}/{#CONTROLLER_NAME#}?function={$actions[action][1]}&id={$objects[object]->id}"><div class="action" id="action">{$actions[action][0]}</div></a>
    
{/section}
</div>
{/section}
</div>
