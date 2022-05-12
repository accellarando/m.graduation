<?php

include 'classes/dbConn.php';

$order_id = mysqli_real_escape_string($link, $order_id);
$order = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM orders WHERE id=$order_id"));

if($order==NULL){
	echo "Order ID not found!<br>";
	echo "<a href='order_lookup.php'>Go back</a>";
	die();
}

$unid    = $order['uid'];
$name    = $order['name'];
$heightF = $order['feet'];
$heightI = $order['inches'];
$weight  = $order['weight'];
$degreeType = $order['degree_type'];
$college  = $order['college'];
$pkg	  = $order['package'];

if($college == "Medicine"){
	$fieldOfStudy = mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM medicine WHERE orderID=$order_id"))['fieldOfStudy'];
}

//Putting together the itemsArray
$itemsArray = mysqli_fetch_all(mysqli_query($link,"SELECT * FROM ordereditems WHERE orderID=$order_id"),MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html>
	<head>
		<style>
h4 { 
	display: inline; 
	margin-left: 15px;

}

		#barcodesTable {
			width: 95%;
			border: solid black;
			margin-top: 20px;
		}

		th, td {
			width: 30%;
			text-align: center;
		}

		tr {
			/*margin-bottom: 15px;*/
			border-bottom-style: solid; 
		}

		table {
			border-style: solid;
			border-collapse: collapse;
			border-color: #d3d3d3;
		}

		/* Breathing room for barcodes */
		td {
			height: 130px;
		}

		.tabme {
			margin-left: 15px;
			padding-left: 15px;
			position: relative;
			top:-15px;
		}

		</style>

		<?php if(isset($redirectScript)) echo $redirectScript; ?>

	</head>
	<body <?php if(isset($redirectScript)) echo 'onload="printThenRedirect()"'; ?>>
		<h2>Personal Information: </h2>
		<h4>Uid:</h4>     <?php echo $unid; ?> 
		<h4>Name:</h4>    <?php echo $name; ?>
		<h4>Height:</h4>  <?php echo $heightF."' ".$heightI."\""; ?>
		<h4>Weight:</h4>  <?php echo $weight. "lbs."; ?><br>
		<h4>Degree:</h4>  <?php echo $degreeType; ?>
		<h4>College:</h4> <?php echo $college; ?>
		<!-- <h4>Commencement Date:</h4> <?php //echo $date; ?><br> -->
<?php
if (!empty($fieldOfStudy)) {
	echo '<h4>Field of Study: </h4>'.$fieldOfStudy.' <br>';
}
?>


<h2>Order Information:</h2>
<table id="barcodesTable">
	<tr>
		<th>Item</th>
		<th>Barcode</th>
		<th>Qty.</th>
		</	tr>
		<h4>Order ID:</h4>     <?php echo $order_id; ?> 
<?php

// ----- PACKAGES ----- //
if ($pkg != "None") {

	$query = "SELECT * FROM packages WHERE name='".$pkg."'"; // does name match column name?
	$result = mysqli_query($link, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		$sku = $row['sku'];
		if($display=="email"){
			$mail->AddEmbeddedImage("skus/$sku.gif","$sku.gif");
			echo "<tr>
				<td>$pkg</td>
				<td><img src='cid:$sku.gif' alt='$sku'></td>
				<td>1</td>
				</tr>";
		}
		else{
			echo "<tr>
				<td>$pkg</td>
				<td><img src='skus/$sku.gif' alt='$sku'></td>
				<td>1</td>
				</tr>";
		}
	}
}


// ---- INDIVIDUAL ITEMS ---- //	
if (count($itemsArray) > 0){

	// Items for this order  						
	$query = "SELECT * FROM items I JOIN (SELECT * FROM ordereditems O WHERE O.orderID= ".$order_id.") AS ordItems ON I.sku=ordItems.itemID"; 
	$result = mysqli_query($link, $query);

	// Display item name from from items, barcode, quantity
	while($row = mysqli_fetch_assoc($result)){ 
		$sku = $row['sku'];
		if($display=="email"){
			$mail->AddEmbeddedImage("skus/$sku.gif","$sku.gif");

			echo "<tr>
				<td>{$row['name']}</td>
				<td><img src='cid:$sku.gif' alt='$sku'></td>
				<td>{$row['quantity']}</td>
				</tr>";
		}
		else{
			echo "<tr>
				<td>{$row['name']}</td>
				<td><img src='skus/$sku.gif' alt='$sku'></td>
				<td>{$row['quantity']}</td>
				</tr>";
		}

	}
}


?>

</table>
	</body>	
</html>


