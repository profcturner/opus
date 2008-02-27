<div id="transcripipt">
  {section name=module loop=$transcripts}
    <div id="module" 
      {if $smarty.section.transcript.iteration is odd}
        class='list_row_light'
      {else}
        class='list_row_dark'
      {/if}
    >
      <div id="module_code" 
        {if $transcripts[module].module.module_tot_grade != 'P' 
          && $transcripts[module].module.module_tot_grade !='M' 
          && $transcripts[module].module.module_tot_grade !='D' 
          && $transcripts[module].module.module_tot_grade !=''}
            {if ($transcripts[module].module.resit_module_tot_grade =="F" 
              || $transcripts[module].module.resit_module_tot_grade=="") 
              && $transcripts_enabled}
                class="failed_cell"
            {/if}
        {/if}
      >
        {$transcripts[module].module.module_code}
      </div>

      <div id="module_title">
        <a href="{#APPLICATION_URL#}{#APPLICATION_CONTROLLER#}?function=view_module_details&occurrence_code={$transcripts[module].module.occurrence_code}&unit_code={$transcripts[module].module.module_code}">
          {$transcripts[module].module.module_title}
        </a>
      </div>
  
      <div id="occurrence">
        {$transcripts[module].module.occurrence_code|regex_replace:"/[A-Z]+.-/":""|regex_replace:"/ASYR/":""}
      </div>

      <div id="occurrence">
        {$transcripts[module].module.occurrence_code|truncate:4:""|regex_replace:"/ASYR/":"Full Year"}
      </div>

      <div id="credit_points">
        {$transcripts[module].module.credit_points}
      </div>

      <div id="credit_level">
        {$transcripts[module].module.credit_level}
      </div>

      {if 1}

      <div id="coursework">{if $transcripts[module].module.resit_module_cw_mark}
                {$transcripts[module].module.resit_module_cw_mark}
                {else}
                {$transcripts[module].module.module_cw_mark}
                {/if}</div>
      <div id="exam">{if $transcripts[module].module.resit_module_ex_mark}
                 {$transcripts[module].module.resit_module_ex_mark}
                 {else}
                {$transcripts[module].module.module_ex_mark}
                {/if}</div>
      <div id="total">{if $transcripts[module].module.resit_module_tot_mark}
                {$transcripts[module].module.resit_module_tot_mark}
                {else}
                {$transcripts[module].module.module_tot_mark}
                {/if}</div>
      {else}
        <div id="coursework"></div>
        <div id="exam"></div>
        <div id="total"></div>
      {/if}
    </div>
  {/section}
</div>