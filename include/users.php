<?php

/**	users.php
**
** Background code used for making new users.
**
** Initial coding : Colin Turner
**
*/

// URGENT FIX REQUIRED, this include is causing lots of bizarre problems :-(.
//include('automail.php');

function user_make_username($title, $firstname, $surname)
{
  // $title is not used - at least for now...
  // Strip all characters that aren't alphabetical
  // and make everything lower case.
  $firstname = strtolower(ereg_replace("[^[:alpha:]]", "", $firstname));
  $surname   = strtolower(ereg_replace("[^[:alpha:]]", "", $surname));

  // Make an initial guess...
  $attempt = substr($firstname, 0, 1) . substr($surname, 0, 8);

  for($loop = 0; $loop < 10; $loop++)
  {
    // Add a number if required;
    if($loop == 0) $full_attempt = $attempt;
    else $full_attempt = $attempt . $loop;

    $query  = "SELECT * FROM id WHERE username='$full_attempt'";
    $result = mysql_query($query)
      or print_mysql_error2("Unable to query id table.", $query);

    if(!mysql_num_rows($result)){
      mysql_free_result($result);
      return($full_attempt);
    }
    mysql_free_result($result);
  }
  // No guess worked, we can improve this later, but it's improbable
  // we will end up here.
  return(FALSE);
}


function user_make_password()
{ 
  // Removed l and 1 to prevent font confusion.
  // Removed O and 0 to prevent font confusion
  // Create an array of valid password characters. 
  $the_char = array( 
     "a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
     "k","K","L","m","M","n","N","o","p","P","q","Q","r","R","s","S","t","T",
     "u","U","v","V","w","W","x","X","y","Y","z","Z","2","3","4","5","6","7","8",
     "9"
  ); 

  // Set var to number of elements in the array minus 1, since arrays begin at 0 
  // and the count() function returns beginning the count at 1. 
  $max_elements = count($the_char) - 1; 

  // Now we set our random vars using the rand() function with 0 and the  
  // array count number as our arguments. Thus returning $the_char[randnum]. 

  srand((double)microtime()*1000000);
  $password[0] = $the_char[rand(0,$max_elements)];  
  $password[1] = $the_char[rand(0,$max_elements)];  
  $password[2] = $the_char[rand(0,$max_elements)];  
  $password[3] = $the_char[rand(0,$max_elements)];  
  $password[4] = $the_char[rand(0,$max_elements)];  
  $password[5] = $the_char[rand(0,$max_elements)];  
  $password[6] = $the_char[rand(0,$max_elements)];  
  $password[7] = $the_char[rand(0,$max_elements)];
  
  return(implode("", $password));
}


function user_notify_password($email, $title, $firstname, $surname, $username, 
			      $password, $user_id, $template="NewPassword")
{
  $mailfields = array();
  $mailfields["atitle"] = "Dr.";
  $mailfields["afirstname"] = "Colin";
  $mailfields["asurname"] = "Turner";  
  $mailfields["aposition"] = "Webmaster";
  $mailfields["aemail"] = "c.turner@ulster.ac.uk";
  $mailfields["rtitle"] = $title;
  $mailfields["rfirstname"] = $firstname;
  $mailfields["rsurname"] = $surname;
  $mailfields["username"] = $username;
  $mailfields["password"] = $password;
  $mailfields["remail"]   = $email;

  automail($template, $mailfields);
}

?>