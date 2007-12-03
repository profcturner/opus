<?php
/**
* Encapulates interfaces to several CV sources
* @package OPUS
*/

/**
* Encapulates interfaces to several CV sources
*
* OPUS can obtain CVs from
* <ul>
*  <li>its internal CV store for students, derived from PDSystem code;</li>
*  <li>PDSystem stored CVs, identical to that above, but remote;</li>
*  <li>PDSystem template based CVs, that are almost miny e-Portfolios.</li>
* </ul>
* This class is designed to provide more seamless access to these.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @see CV.class.php
* @see PDSystem.class.php
* @package OPUS
*
*/

class CVCombined
{
  /**
  * email a CV from a student to a given recipient
  *
  */
  function email_cv($student_id, $recipient_id, $vacancy_id = 0)
  {
  }
  
  
};

?>