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

  
</td></tr></table>
<div id="footer">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
  <td>
  {$conf.appname}, Version {$opus_version} |
    <a href="{$td width="1%" colspan="2"conf.scripts.user.helpdir}{if $student_id}?student_id={$student_id}{/if}">
For help, please try the Help Directory</a>
    </a> |
    <a href="{$conf.paths.base}about.php">About OPUS</a> 
{if $conf.support_site.reportbug}
 | <a href="{$conf.support_site.reportbug}">Report a Bug or Request a Feature</a>
{/if}
  </td>
  <td class="align-right">
  <!-- Begin benchmark -->
  <small>Compile time:  {$page->endtime-$page->starttime|string_format:"%.2f"}  seconds.</small>
  <!-- End benchmark -->
  </td>
</tr>
</table>
</div>
</body>
</html>
