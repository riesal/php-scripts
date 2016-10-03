<?php
## cast array to string and get the last 'column' by sxploding ' '

$bluemix = ' ';

$bluemix = file_get_contents("http://my.api.intispace.com/stats/");
echo $bluemix;

if (preg_match("/\bInvalid IP\b/i", $bluemix)) {
    $json = json_decode($bluemix, true);
    $my_ip = $json['message'];
    $my_ip = $my_ip . PHP_EOL;
    $my_ip = (string) $my_ip;
    # $my_ip = end(explode(' ', $my_ip)); OR
    # $my_ip = array_pop(explode(' ', $my_ip));
    # applying the variable above will produce: 
    # Strict Standards: Only variables should be passed by reference
    # so, the fix is just putting things outside of things, like below:
    $is_explode = explode(' ', $my_ip);
    $my_ip = strtolower(array_pop($is_explode));
    echo "The IP is invalid, adding this IP: " . $my_ip . " to WHMCS\n";
}
else
{
    echo "The IP is valid, nothing todo.\n";
}
?>
