{if $mode == "add" || $mode == "edit"}

{* javascript for calendar, counter and bb code *} 

  <script src="{$config.opus.url}/javascript/bb_code.js" type="text/javascript"></script>
  <script src="{$config.opus.url}/javascript/textcounter.js" type="text/javascript"></script>
  <link href="{$config.opus.url}/css/CalendarControl.css"
      rel="stylesheet" type="text/css">
  <script src="{$config.opus.url}/javascript/CalendarControl.js"
        language="javascript"></script>

{* javascript to do the ajax object validation calls, this is used in the add and edit mode *}

{literal}
<script type="text/javascript">
  var XMLHttpRequestObject = false;

  if (window.XMLHttpRequest) {
    XMLHttpRequestObject = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }

  function getData(dataSource, divID)
  {
    if(XMLHttpRequestObject) {
      var obj = document.getElementById(divID);
      XMLHttpRequestObject.open("GET", dataSource);

      XMLHttpRequestObject.onreadystatechange = function()
      {

        if (XMLHttpRequestObject.readyState == 4 &&
          XMLHttpRequestObject.status == 200) {
            obj.innerHTML = XMLHttpRequestObject.responseText;
        }
      }
      XMLHttpRequestObject.send(null);
    }
  }

  function evalData(dataSource, divID)
  {
    if(XMLHttpRequestObject) {
      var obj = document.getElementById(divID);
      XMLHttpRequestObject.open("GET", dataSource);

      XMLHttpRequestObject.onreadystatechange = function()
      {

        if (XMLHttpRequestObject.readyState == 4 &&
          XMLHttpRequestObject.status == 200) {
            divID = eval(XMLHttpRequestObject.responseText);
        }
      }
      XMLHttpRequestObject.send(null);
    }
  }

  function DataValueByID (divID) {

    var obj = document.getElementById(divID);
    return obj.value;

  }
</script>
{/literal}

{/if}

<div id="table_manage">
<form method="POST" ENCTYPE="multipart/form-data" action="" name="mainform">
<table cellpadding="0" cellspacing="0" border="0">
{if $action_button}

  <tr>
    <td colspan="{if $mode == add || $mode == edit}3{else}2{/if}" class="button"><input type="submit" class="submit" value="{$action_button[0]}" /><input type="hidden" name="section" value="{$action_button[1]}" /><input type="hidden" name="function" value="{$action_button[2]}" /><input type="hidden" name="id" value="{$object->id}" /></td>
  </tr>
{/if}
{* loop through each field of the object *}

{foreach from=$headings key=header item=def}
  <tr>
    <td class="property">{if $def.title}{$def.title}{else}{$header|replace:"_":" "|capitalize}{/if}</td>

{* td to hold the validation image, the class validation can be used to set this width *}

{if $mode == "add" || $mode == "edit"}
    <td class="validation"><span class="validation_message" id="{$header}_validation">{$validation_messages[$header]}</span></td>
{/if}

    <td>
{if $mode == "view" || $mode == "remove"}

      {if $def.type == "lookup"}
        {eval assign=header2 var="_$header"}
        {$object->$header2}
      {elseif $def.type == "email"}
        <a href="mailto:{$object->$header}">{$object->$header}</a>
      {elseif $def.type == "url"}
        <a href="{$object->$header}">{$object->$header}</a>
      {elseif $def.type == "date"} 
        {$object->$header|date_format}
      {elseif $def.type == "currency"}
        &pound;{$object->$header|string_format:"%.2f"}
      {else}
        {$object->$header|nl2br}
      {/if}


{elseif $mode == "add"}

      {if $def.type == "text"}
        <input type="text" name="{$header}" size="{$def.size|default:40}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {elseif $def.type == "email"}
        <input type="text" name="{$header}" size="{$def.size|default:60}" style="color:#000099;" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {elseif $def.type == "url"}
        <input type="text" name="{$header}" size="{$def.size|default:60}" style="color:#000099;" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {elseif $def.type == "textarea"}
        {include file='textarea_toolbar.tpl' textarea=$header}<span class="info_label">Characters Remaining</span><input readonly class='text_counter' type="text" name="{$header}_Len" size="6" maxlength="6" value="{if $def.maxsize}{$def.maxsize}{else}{math equation='x * y' x=$def.rowsize y=$def.colsize}{/if}" class="text_counter">
        <textarea rows="{$def.rowsize|default:6}" cols="{$def.colsize|default:60}" name="{$header}"  id="{$header}_{$object->id}"  onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}  onKeyDown="textCounter(document.mainform.{$header},document.mainform.{$header}_Len,{if $def.maxsize}{$def.maxsize}{else}{math equation='x * y' x=$def.rowsize y=$def.colsize}{/if});getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" onKeyUp="textCounter(document.mainform.{$header},document.mainform.{$header}_Len,{if $def.maxsize}{$def.maxsize}{else}{math equation='x * y' x=$def.rowsize y=$def.colsize}{/if});getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');">{$nvp_array[$header]|default:$object->$header}</textarea>
      {elseif $def.type == "flexidate"}
        <input type="text" name="{$header}" size="{$def.size|default:15}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if} /> {#flexidate#}
      {elseif $def.type == "date"}
        {if $def.inputstyle == "popup"}
          <input type="text" name="{$header}" size="{$def.size|default:15}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/> <input type='button' onclick="showCalendarControl(document.mainform.{$header});" class='calendar_button' title='click to see calendar'/>
        {else}
          {html_select_date prefix=$def.prefix day_empty="day" month_empty="month" year_empty="year" time="$workDate" start_year=$def.year_start|default:"1900" end_year=$def.year_end|default:"2100" field_order="DMY" day_value_format="%02d"}
        {/if}
      {elseif $def.type == "image" || $def.type == "file"}
        <input type="file" name="{$header}" size="{$def.size}"/>
      {elseif $def.type == "list"}
        {html_options values=$def.list output=$def.list name=$header}
      {elseif $def.type == "lookup"}
        {html_options name="$header"}
        {php} 
        echo smarty_function_html_options(array('name' => $this->_tpl_vars['header'],'options' => $this->_tpl_vars[$this->_tpl_vars['def']['var']], 'selected' => $this->_tpl_vars[$this->_tpl_vars['header']]), $this);
        {/php}
      {elseif $def.type == "numeric"}
        <input type="text" name="{$header}" size="{$def.size}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
         {elseif $def.type == "duration"}
            <input type="text" name="{$header}" size="{$def.size}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>&nbsp;{$def.scale}
      {elseif $def.type == "currency"}
        &pound; <input type="text" name="{$header}" size="{$def.size}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {elseif $def.type == "percentage"}
                <input type="text" name="{$header}" size="{$def.size}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/> %
            {else}
        <input type="text" name="{$header}" size="{$def.size|default:50}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {/if}


{elseif $mode == "edit"}

      {if $def.type == "text"}
      <input type="text" name="{$header}" id="{$header}_{$object->id}" size="{$def.size|default:40}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {elseif $def.type == "textarea"}
      {include file='textarea_toolbar.tpl' textarea=$header}<span class="info_label">Characters Remaining</span><input readonly class='text_counter' type="text" name="{$header}_Len" size="6" maxlength="6" value="{if $def.maxsize}{$def.maxsize}{else}{math equation='x * y' x=$def.rowsize y=$def.colsize}{/if}" class="text_counter">
      <textarea rows="{$def.rowsize|default:6}" cols="{$def.colsize|default:60}" name="{$header}"  id="{$header}_{$object->id}"  onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}  onKeyDown="textCounter(document.mainform.{$header},document.mainform.{$header}_Len,{if $def.maxsize}{$def.maxsize}{else}{math equation='x * y' x=$def.rowsize y=$def.colsize}{/if});getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" onKeyUp="textCounter(document.mainform.{$header},document.mainform.{$header}_Len,{if $def.maxsize}{$def.maxsize}{else}{math equation='x * y' x=$def.rowsize y=$def.colsize}{/if});getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');">{$nvp_array[$header]|default:$object->$header}</textarea>
      {elseif $def.type == "email"}
      <input type="text" name="{$header}" id="{$header}_{$object->id}" size="{$def.size|default:60}" value="{$nvp_array[$header]|default:$object->$header}" style="color:#000099;" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {elseif $def.type == "url"}
      <input type="text" name="{$header}" size="{$def.size|default:60}" id="{$header}_{$object->id}"  value="{$nvp_array[$header]|default:$object->$header}" style="color:#000099;" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {elseif $def.type == "currency"}
      &pound; <input type="text" name="{$header}" size="{$def.size|default:60}" id="{$header}_{$object->id}"  value="{$nvp_array[$header]|default:$object->$header|string_format:'%.2f'}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
{elseif $def.type == "percentage"}
            <input type="text" name="{$header}" size="{$def.size|default:60}" id="{$header}_{$object->id}"  value="{$nvp_array[$header]|default:$object->$header|string_format:'%.2f'}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/> %
      {elseif $def.type == "numeric"}
      <input type="text" name="{$header}" size="{$def.size|default:60}" id="{$header}_{$object->id}"  value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
         {elseif $def.type == "duration"}
         <input type="text" name="{$header}" size="{$def.size|default:60}" id="{$header}_{$object->id}"  value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>&nbsp;{$def.scale}
      {elseif $def.type == "date"}
        {if $def.inputstyle == "popup"}
          <input type="text" name="{$header}" size="{$def.size|default:15}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if} onFocus='showCalendarControl(this);'/>
        {else}
          {html_select_date prefix=$def.prefix day_empty="day" month_empty="month" year_empty="year" time=$object->$header start_year=$def.year_start end_year=$def.year_end field_order="DMY" day_value_format="%02d"}
        {/if}
      {elseif $def.type == "flexidate"}
        <input type="text" name="{$header}" size="{$def.size|default:15}" id="{$header}_{$object->id}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/> {#flexidate#}
      {elseif $def.type == "image" || $def.type == "file"}
      {if $object->$header}<a href="">{$object->$header}</a>
      {else}
      <input type="file" name="{$header}" size="{$def.size}"/>
      {/if}
      {elseif $def.type == "list"}
      {html_options values=$def.list output=$def.list selected=$object->$header name=$header}
      {elseif $def.type == "lookup"}
      {html_options name="$header"}
{php} 
echo smarty_function_html_options(array('name' => $this->_tpl_vars['header'],'options' => $this->_tpl_vars[$this->_tpl_vars['def']['var']], 'selected' => $this->_tpl_vars['object']->{$this->_tpl_vars['header']}), $this);
{/php}
      {elseif $def.type == "postcode"}
      <input type="text" name="{$header}" id="{$header}_{$object->id}" size="{$def.size}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
         {elseif $def.type == "link"}
         <a href="{#APPLICATION_URL#}/{#CONTROLLER_NAME#}?function={$def.url}&id={$object->id}">{$nvp_array[$header]|default:$object->$header}</a>
      {else}
      <input type="text" name="{$header}" size="{$def.size|default:50}" value="{$nvp_array[$header]|default:$object->$header}" onChange="getData('index.php?function=validate_field&object={$object->_get_tablename()}&field={$header}&value='+DataValueByID('{$header}_{$object->id}'),'{$header}_validation');" {if $validation_messages[$header]}class="validation_failed"{/if}/>
      {/if}

{/if}
    </td>
  </tr>
{/foreach}

{if $action_button}

  <tr>
    <td colspan="{if $mode == add || $mode == edit}3{else}2{/if}" class="button"><input type="submit" class="submit" value="{$action_button[0]}" /><input type="hidden" name="section" value="{$action_button[1]}" /><input type="hidden" name="function" value="{$action_button[2]}" /><input type="hidden" name="id" value="{$object->id}" /></td>
  </tr>
{/if}
</table>
{section loop=$hidden_values name=hidden_value}
<input type="hidden" name="{$hidden_values[hidden_value][0]}" value="{$hidden_values[hidden_value][1]}" />
{/section}
</form>
</div>