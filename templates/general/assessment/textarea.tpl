{* Smarty *}
{* assessment/textarea.tpl *}
{* Helper to embed a textarea, with support for character counting *}
{* and, optionally, error flagging *}
{foreach from=$assessment->structure item=test_item}
{if $test_item->name == $name}{eval assign="max_length" var=$test_item->max}{/if}
{/foreach}
{if $max_length}<span class="info_label">Characters Remaining</span><input readonly class='text_counter' type="text" name="{$name}_Len" size="6" maxlength="6" value="{$max_length}" class="text_counter"><br />{/if}<textarea id="assessmentfield_{$name}" name="{$name}" rows="{$rows}" cols="{$cols}" wrap="virtual" {if $max_length}onKeyUp="textCounter(document.mainform.{$name},document.mainform.{$name}_Len,{$max_length});"{/if}>{$assessment->get_value($name)|escape:"htmlall"}</textarea>