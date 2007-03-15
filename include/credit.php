<?php

/**
**	credit.php
**
** Includes credit and version information for
** the suite of webpages.
**
** Initial coding : Colin Turner
**
*/

$PlacementSystemVersion = "1.00.b2";
$PlacementSystemDate    = "26th October 2001";


/**
**	display_credits()
**
** This function displays version and credit
** information for the suite of webpages.
** It also shows a copyright message.
*/
function display_credits()
{
  global $PlacementSystemVersion;
  global $PlacementSystemDate;

  printf("<H2 ALIGN=\"CENTER\">Version & Credits</H2>\n");

  printf("<P ALIGN=\"CENTER\">This is the placement administration ");
  printf("suite of webpages.</P>");

  printf("<P ALIGN=\"CENTER\">Written by Andrew Hunter and ");
  printf("Colin Turner for the School of Electrical and Mechanical ");
  printf("Engineering at the University of Ulster.</P>\n");

  printf("<TABLE ALIGN=\"CENTER\" BORDER=\"1\">\n");
  printf("<TR><TD>Version</TD><TD>%s</TD></TR>\n",
         $PlacementSystemVersion);
  printf("<TR><TD>Released</TD><TD>%s</TD></TR>\n",
         $PlacementSystemDate);

  printf("<P ALIGN=\"CENTER\">This work is protected by copyright.</P>\n");
}


