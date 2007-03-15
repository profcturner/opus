<?php

/**
**	log.php
**
** This file provides a class that performs logging
** operations.
**
** Initial coding : Colin Turner
**
*/


class Log
{
   var $file;  // File pointer, NULL indicates no logging
   var $user;  // Currently authenticated user (or NULL)
   var $level; // A debug level see debug()

   /**
   **	Log()
   **
   ** This constructor attempts to open the named filename
   ** unless the filename is empty in which case it sets
   ** $file to NULL. This also indicates failure to open a
   ** file and inhibits further access to the object.
   **
   ** We also set the username to log against all actions,
   ** although this could clearly be NULL.
   **
   */  
   function Log($filename, $username)
   {
     global $conf; // Need access to configuration

     // Attempt to open the file
     if(empty($filename)) $this->file = NULL;
     else $this->file = fopen($filename, "a");
     
     if(!($this->file)){
       // File did not open
       if($conf["debug"])
         printf("<P ALIGN=\"CENTER\">Log %s failed to open</P>\n",
                 htmlspecialchars($filename));
     }
     
     // Record authenticated user
     $this->user = $username;

     // Set debugging to minimum by default
     if($conf["debug"]) $this->level = 9;
     else $this->level = 0;
   }
   
   function SetUserName($username)
   {
     $this->user = $username;
   }

   /**
   **	SetLevel()
   **
   ** This function sets the internal debug level
   ** indicator to the passed value. This should be
   ** an integer from 0 (no debug) to 9 (maximum).
   */
   function SetLevel($debuglevel)
   {
     $this->level = $debuglevel;
     if($this->level > 9) $tihs->level = 9;
   }

   /**
   **	LogPrint()
   **
   ** This function prints the specified line to the
   ** log regardless of error level, assuming the log
   ** is active and open.
   **
   ** An appropriate datestamp is added before hand,
   ** and the user name if appropriate.
   */
   function LogPrint($line)
   {
     global $conf; // We need access to the configuration

     if(!($this->file)) return; // File is not open
     
     if($this->user) $user_segment = sprintf("[%s] ", $this->user);
     else $user_segment = "";
     
     $line = sprintf("%s %s%s\n", date($conf["logs"]["date"]), $user_segment, $line);
     fwrite($this->file, $line);
   }
   
   
   /**
   **	LogDebug()
   **
   ** Similar to print() but a level is specified, and
   ** the print only occurs if the internal level is
   ** at least equal to the level specified.
   **
   */
   function LogDebug($debuglevel, $line)
   {
     if($debuglevel >= $tihs->level) $this->LogPrint($line);
   }
}

?>