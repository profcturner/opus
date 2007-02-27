<?php

/**
**  @filename cv.php
**
** Some backend code used by the various CV scripts.
*/

/**
**  @function cv_link
**  Provides a hyperlink to the relevant CV PDF viewer
**  This is provided now to all expansion in the future to take account
**  of viewer preferences.
**  @param $student_id  The id of the student to link to
**  @param $cv_id (Optional) the cv template to use
** @return the url of the CV
*/
function cv_link($student_id, $cv_id=0, $year=0)
{
  global $conf; // Need access to configuration

  if($year==0) $year = get_placement_year($student_id);

  // Support for classic CV first
  if($year > 2004)
  {
    if(!$cv_id) $cv_id=get_default_cvtemplate($student_id);
    $url = $conf['scripts']['student']['pdpcvpdf'] . 
      "?student_id=$student_id&template_id=$cv_id";
  }

  if($year == 2004)
  {
    $cv_id=5; // Ugly hardcode to default placement CV
    // So it's a new CV then
    $url = $conf['scripts']['student']['newviewcvpdf'] .
      "?student_id=$student_id&id=$cv_id";
  }
    
  if($year < 2004)
  {
    $url = $conf['scripts']['student']['viewcvpdf'] .
      "?student_id=$student_id";
  }
  return $url;
}

/**
* Provides a URL for fetching from the CV archive
* Note that the hash is deliberately not included, so that it can be
* concealed behind OPUS's security.
* @param integer $student_id the id of the student to link to
* @param integer $vacancy_id the vacancy they used to make the application
* @return the URL required
*/
function archive_cv_link($student_id, $vacancy_id)
{
  global $conf;

    $url = $conf['scripts']['student']['pdpcvpdf'] . 
      "?student_id=$student_id&vacancy_id=$vacancy_id";

    return($url);

}


?>
