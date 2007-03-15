<?php

class Cookie  {

    var $secr="your_secret_goes_here";

    function read($cookie_name) {

        $cookie = array();

        $pairs = explode("&",$_COOKIE[$cookie_name]);
        foreach($pairs as $pair) {
            $eq = explode("=", $pair);
            $cookie[$eq[0]] = $eq[1];           
        }
        ///print_r($cookie);/
        if (Cookie::verify($_COOKIE[$cookie_name])) return $cookie;
        else return FALSE;
        ///Cookie::verify($_COOKIE[$cookie_name], $cookie);/
    }
    function verify($cookie) {

        $cookie_array = array();

        $pairs = explode("&",$cookie);
        foreach($pairs as $pair) {
            $eq = explode("=", $pair);
            $cookie_array[$eq[0]] = $eq[1];           
        }
        // CT Modification
        if($cookie_array['expire'] < time()) return FALSE;

        if (Cookie::hash(substr($cookie,0,-38)) == $cookie_array[hash]) {
            return True;
        } else {
            return False;
        }

    }
    function write($cookie_name, $cookie_value, $cookie_expire="", $cookie_path="/", $cookie_host=".ulster.ac.uk") {

        if ($cookie_name) {

            if (strlen($cookie_expire) == 0) $cookie_expire=time()+1800;

            $cookie_value .= "&expire=$cookie_expire";
            $hash = Cookie::hash($cookie_value);
            $cookie_value .= "&hash=$hash";
            setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path, $cookie_host, 0);
            ///setcookie("PDSTicket[reg_number]", "1111", $cookie_expire, "/", ".ulster.ac.uk", 0);/
            ///print($cookie_name);/
        }

    }
    function delete() {

    }

    function hash($value) {
       
        return md5($secr.$value);

    }

}
?>
