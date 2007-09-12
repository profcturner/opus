<?php
/**
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