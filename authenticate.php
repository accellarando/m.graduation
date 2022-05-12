<?php
	session_start();
	
	$ip = $_SERVER['REMOTE_ADDR'];
	//echo $ip;
	$url = "print.php?order_id=".$_GET['order_id'];
	if(substr($ip,0,10)=="155.101.88" || $ip=="127.0.0.1" || $ip=="::1"){
		$_SESSION['verified'] = "IPspace";
		header("Location: ".$url);
	}
	
	if($_POST['pass']=="PASSWORD"){
		$_SESSION['verified']="password";
		header("Location: ".$url);
	}
	
	else
		echo "Verification error. Contact IT. <br>";
?>
You can also login automatically from wired Campus Store IP space.
<form method="POST" action="authenticate.php?order_id=<?php echo $_GET['order_id']; ?>">
	Password: <input type="password" name="pass">
	<button type="submit">Submit</submit>
</form>
