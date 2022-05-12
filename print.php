<?php 
    session_start();
	
	if(!isset($_SESSION['verified'])){
		header("Location: authenticate.php?order_id=".$_POST['order_id']);
	}
	
	$order_id=0;
	if(!isset($_POST['order_id'])){
		if(isset($_GET['order_id']))
			$order_id = $_GET['order_id'];
		else
			header("Location: order_lookup.php");
	}
	else
		$order_id = $_POST['order_id'];


$redirectScript = "<script>
	function redirect(url){
				window.location.href = url;
			}

	function printThenRedirect(){
				window.print();
				setTimeout('redirect(\"order_lookup.php\")', 2000);
				//redirect('order_lookup.php');
			}
</script>";

$display="print";

require("classes/orderDetails.php");

//Mark completed
mysqli_query($link, "UPDATE orders SET completed = 1 WHERE id=$order_id");
?>
