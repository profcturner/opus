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
  <TD WIDTH="100%" COLSPAN="2" class="align-centre">
  {$conf.appname}, Version {$opus_version}<br />
    <a href="{$conf.scripts.user.helpdir}{if $student_id}?student_id={$student_id}{/if}">
For help, please try the Help Directory</a>
    </a>
{if $conf.support_site.reportbug}
 | <a href="{$conf.support_site.reportbug}">Report a Bug or Request a Feature</a>
{/if}
  </TD>
  </TR>
<TR>
<TD COLSPAN="2" class="align-right">
<!-- Begin benchmark -->
Compile time:  {$page->endtime-$page->starttime|string_format:"%.2f"}  seconds.
<!-- End benchmark -->
</TD>
</TR>
</TABLE>
</div>
</BODY>
</HTML>
