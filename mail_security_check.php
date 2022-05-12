<?php
// First, make sure the form was posted from a browser.
// For basic web-forms, we don't care about anything
// other than requests from a browser:
if(!isset($_SERVER['HTTP_USER_AGENT'])){
   die("Forbidden - You are not authorized to view this page");
   echo("<H1>Error 403 Forbidden</H1><BR>".
                "Invalid form data received, please use the back button to ".
                "return to the form, check your entries and try again. (1)");
   exit;
}

// Make sure the form was indeed POST'ed:
//  (requires your html form to use: action="post")
if(!$_SERVER['REQUEST_METHOD'] == "POST"){
   die("Forbidden - You are not authorized to view this page");
   echo("<H1>Error 403 Forbidden</H1><BR>".
                "Invalid form data received, please use the back button to ".
                "return to the form, check your entries and try again. (2)");
   exit;
}

// Host names from where the form is authorized
// to be posted from:
$authHosts = array("info.campusstore.utah.edu", "localhost", "slc.bookstore.utah.edu", "155.101.88.57, 155.101.88.240, www.info.campusstore.utah.edu");

// Where have we been posted from?
$fromArray = parse_url(strtolower($_SERVER['HTTP_REFERER']));

// Test to see if the $fromArray used www to get here.
$wwwUsed = strpos($fromArray['host'], "www.");

// Make sure the form was posted from an approved host name.
if(!in_array(($wwwUsed === false ? $fromArray['host'] : substr(stristr($fromArray['host'], '.'), 1)), $authHosts)){
   header("HTTP/1.0 403 Forbidden");
   echo("<H1>Error 403 Forbidden</H1><BR>".
                "Invalid form data received, please use the back button to ".
                "return to the form, check your entries and try again. (3)");
       exit;
}
// Attempt to defend against header injections:
$badStrings = array("Content-Type:",
                     "MIME-Version:",
                     "Content-Transfer-Encoding:",
                     "bcc:",
                     "cc:");

// Loop through each POST'ed value and test if it contains
// one of the $badStrings:
foreach($_POST as $k => $v){
   foreach($badStrings as $v2){
       if(strpos($v, $v2) !== false){
           header("HTTP/1.0 403 Forbidden");
           echo("<H1>Error 403 Forbidden</H1><BR>".
                "Invalid form data received, please use the back button to ".
                "return to the form, check your entries and try again. (4)");
               exit;
       }
   }
}
?>
