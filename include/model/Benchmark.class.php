<?php
/**
 * @package PDSystem
 *
 * reg_number -> username
 * user_id -> id
 * last few fields have gone
 */

require_once("pds/dto/DTO_Benchmark.class.php");

Class Benchmark extends DTO_Benchmark  
{  
  function benchmark_php_loop() 
  {

    $start_time = microtime(True);
  
    for ($count=0; $count<1000000; $count++) 
    {
  
      $value = 42;
      $value = $value + 1;
      $value = $value - 1;
  
    }

    $end_time = microtime(True);
    $compile_time = $end_time - $start_time;
  
    return $compile_time;

  }
}