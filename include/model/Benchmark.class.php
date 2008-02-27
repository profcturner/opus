<?php

/**
* Encapsulated timing of events
* @package OPUS
*/

/**
* Encapsulated timing of events
*
* Generally used to time script execution times.
*
* @author Colin Turner <c.turner@ulster.ac.uk>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
* @package OPUS
*
*/

class Benchmark
{
  var $start_time;

  function __construct()
  {
    $this->start_time = microtime(true);
  }

  function elapsed()
  {
    return(microtime(true) - $this->start_time);
  }

  function print_elapsed()
  {
    echo $this->elapsed();
  }
}