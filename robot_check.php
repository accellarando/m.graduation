<?php

$ip = $_SERVER['REMOTE_ADDR'];

//Run this script first to test for illegitimate form entries.
//include "mail_security_check.php";              
//include "header.php";

//Start session and set variables so that you can access them later.
session_start();
foreach ($_SESSION as $key => $value)
	$_SESSION[$key] = "";

foreach ($_POST as $key => $value)
	$_SESSION[$key] = $value;

// your secret key
$secretKey = "SECRET";
if($ip=="127.0.0.1")
	$secretKey = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";

if(isset($_POST['g-recaptcha-response'])){
	$captcha=$_POST['g-recaptcha-response'];
}
if(!$captcha){
	echo '<h2>Please go back and submit check the box that says "I\'m not a robot."</h2>';
	echo "<button type=\"back\" id=\"submit\" onclick=\"history.go(-1);\">Go Back</button>";
	exit;
}
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
$responseKeys = json_decode($response,true);
var_dump($responseKeys);
if($responseKeys['success']==false){
	echo '<h2>There was an error with your form submission: reCAPTCHA error. Please try again.</h2>';
	echo "<button type=\"back\" id=\"submit\" onclick=\"history.go(-1);\">Go Back</button>";

} else {
	//echo "Success";
	header("Location: send_email.php");
	exit();
}

?>
