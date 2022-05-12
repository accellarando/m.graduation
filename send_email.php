<?php
session_start();

include "mail_security_check.php";

require 'vendor/autoload.php'; //Calls Composer for picqer barcode generator

$unid = $_SESSION['unid'];
$fname = trim($_SESSION['fname']);
$lname = trim($_SESSION['lname']);
$heightF = $_SESSION['heightF'];
$heightI = $_SESSION['heightI'];
$weight = $_SESSION['weight'];
$degreeType = $_SESSION['degreeType'];
$college = $_SESSION['college'];
$fieldOfStudy = $_SESSION['fieldOfStudy'];
// Triple, nested ternary assumes only one package is allowed:
$pkg = (empty($_SESSION['bachelorpkg']) == 0) ?  $_SESSION['bachelorpkg'] : 
    ((empty($_SESSION['masterpkg']) == 0) ?  $_SESSION['masterpkg'] : 
    ((empty($_SESSION['phdpkg'])    == 0) ?  $_SESSION['phdpkg'] : 
    "None"));

$itemsArray = $_SESSION['selectedItems'];
$quantitiesArray = $_SESSION['qty'];

// ----------------------------------------

if (!empty($unid) && !empty($fname) && !empty($lname) && !empty($heightF) && !empty($weight) && !empty($degreeType) && !empty($college)) {

    include 'classes/dbConn.php';

    $name = $fname." ".$lname;

    // MAIN ORDER
    $query = "INSERT INTO `orders`(
	`datetime`,
	`uid`,
	`name`,
	`feet`,
	`inches`,
	`weight`,
	`degree_type`,
	`college`,
	`package`
	)
	VALUES (
	    NOW(),
	    '" . mysqli_real_escape_string($link, $unid) . "',
	    '" . mysqli_real_escape_string($link, $name) . "',
	    '" . mysqli_real_escape_string($link, $heightF) . "',
	    '" . mysqli_real_escape_string($link, $heightI) . "',
	    '" . mysqli_real_escape_string($link, $weight) . "',
	    '" . mysqli_real_escape_string($link, $degreeType) . "',
	    '" . mysqli_real_escape_string($link, $college) . "',
	    '" . mysqli_real_escape_string($link, $pkg ). "'

	    )";

    $result = mysqli_query($link, $query) or die(mysqli_error($link));

    $order_id = mysqli_insert_id($link);
    // echo "<p>OrderID: ".$order_id."</p>";

    // SCHOOL OF MEDICINE
    // If S.O.M.
    if ((empty($fieldOfStudy) == 0) && $college == 'Medicine'){
	$FOS = $fieldOfStudy;

	$query = "INSERT INTO `medicine` (orderID,`fieldOfStudy`)
	    VALUES ('" . mysqli_real_escape_string($link, $order_id) . "',
		'" . mysqli_real_escape_string($link, $FOS) . "'
		)";

	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	if ( !$result ) {

	    echo '<div class="alert alert-danger">
		<strong>Warning!</strong> School of Med.
		<p>Error message: '.mysqli_error($con).'</p>
		</div>';
	} 
    }

    // INDIVIDUAL ITEMS
    if (count($itemsArray) > 0) {
	foreach ($quantitiesArray as $key => $value) {
	    if (in_array($key, $itemsArray)) { // If the item ID we have is a selected one

		$itemID = $key; // ItemID is now sku

		foreach ($quantitiesArray[$key] as $key2 => $quantity) {
		    $query = "INSERT INTO `ordereditems` (orderID,`itemID`, `quantity`)
			VALUES ('" . mysqli_real_escape_string($link, $order_id) . "',
			    '" . mysqli_real_escape_string($link, $itemID) . "',
			    '" . mysqli_real_escape_string($link, $quantity) . "'
			    )";
		    $result = mysqli_query($link, $query) or die(mysqli_error($link));
		    if ( !$result  ) {

			echo '<div class="alert alert-danger">
			    <strong>Warning!</strong> Individual items.
			    <p>Error message: '.mysqli_error($link).'</p>
			    </div>';
		    } 
		}
	    }
	}
    }

    session_destroy();

    /* *** EMAIL CONFIRMATION *** */
    $sendError = false;
    $errorInfo;
    require("../Libraries/PHPMailer_v5.1/class.phpmailer.php");
    $mail = new PHPMailer(true);
    try{

	$webmaster_email = "no-reply@bookstore.utah.edu"; //Reply to this email ID
	$mail->From = $webmaster_email;
	$mail->FromName = "University Campus Store";
	$mail->AddReplyTo($webmaster_email,"Webmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->IsHTML(true); // send as HTML
	$mail->Subject = "Your Regalia Order Confirmation #$order_id";

	$email = $_SESSION['email'];
	$mail->AddAddress($email, $fname." ".$lname);
	$mail->AddAddress("orderfulfillment@bookstore.utah.edu", "University Campus Store");
	$mail->AddAddress("emoss@campusstore.utah.edu", "Ella Moss");


	//Constructing the body
	$body = "<h1>Your order has been confirmed.</h1>";
	$display = "email";
	ob_start();
	include "classes/orderDetails.php";
	$buffer = ob_get_clean();
	//echo $buffer;

	$body = $body.$buffer."<br>";

	$body = $body."For assistance, contact Order Fulfillment at 801-585-3234.</br>";

	//echo $body;

	$mail->Body = $body;

	$mail->Send();
    }catch(phpmailerException $e){
	$sendError = true;
	$errorInfo = $e->errorMessage();
    }catch (Exception $e){
	$sendError = true;
	$errorInfo .= $e->getMessage();
    }
    //Send the email, report an error if the send fails
    if($sendError) {
	echo "<p>Mailer Error: " . $mail->ErrorInfo;
	echo "<br>We got your order, but couldn't send you the confirmation. Contact Order Fulfillment at 801-585-3234 for assistance.</p>";
	echo "Your order ID is $order_id.";
	die();
    }


}

else {
    echo "Order not submitted. Please fill out all required fields.";
    die();
}

?>
<html>
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"> 
	<link rel="stylesheet" type="text/css" href="mobileStyle.css"/>
	<title>Order Confirmed</title>
    </head>

    <body>
	<div class="container">
	    <!-- U Logo here? -->
	    <br>
	    <div class="card">
		<div class="card-header text-header"> Order Confirmed </div>
		<div class="card-body text-center">
		    Show this order confirmation code to the employee to pick up your order.<br>
		    A copy has also been sent to your email.<br>
		    <h1><?php echo $order_id; ?></h1>
		</div>
		<div style="margin:auto;">
<?php
$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode($order_id,$generator::TYPE_CODE_128);
?>
<br>
		</div>

	    </div>
	</div>
    </body>

</html>
