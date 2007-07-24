{* Smarty *}

{* Used to close pages *}
{* Finish the table started elsewhere *}


<!-- Footer begins -->

</td>
  </tr> 
  </table>

  
      </div> <!-- main content area end-->
      
    </div>  <!-- container ends -->
  
  </div> <!-- content ends -->

  
</TD></TR></TABLE>
<div id="footer">
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
  <TR>
  <TD WIDTH="100%" COLSPAN="2">
  {$conf.appname}, Version {$opus_version} |
    <a href="{$conf.scripts.user.helpdir}{if $student_id}?student_id={$student_id}{/if}">
For help, please try the Help Directory</a>
    </a> |
    <a href="{$conf.paths.base}about.php">About OPUS</a> 
{if $conf.support_site.reportbug}
 | <a href="{$conf.support_site.reportbug}">Report a Bug or Request a Feature</a>
{/if}
  </TD>
  <TD COLSPAN="2" class="align-right">
  <!-- Begin benchmark -->
  <small>Compile time:  {$page->endtime-$page->starttime|string_format:"%.2f"}  seconds.</small>
  <!-- End benchmark -->
  </TD>
</TR>
</TABLE>
</div>
</BODY>
</HTML>
