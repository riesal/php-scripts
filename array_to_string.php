<?php
## cast array to string and get the last 'column' by sxploding ' '

$bluemix = ' ';

$bluemix = file_get_contents("http://my.api.intispace.com/stats/");
echo $bluemix;

if (preg_match("/\bInvalid IP\b/i", $bluemix)) {
    $json = json_decode($bluemix, true);
    $ipku = $json['message'];
    $ipku = $ipku . PHP_EOL;
    $ipku = (string) $ipku;
    $ipku = end(explode(' ', $ipku));
    #$ipku = array_pop(explode(' ', $ipku));
    echo "The IP is invalid, adding this IP: " . $ipku . " to WHMCS\n";
}
else
{
    echo "The IP is valid, nothing todo.\n";
}
