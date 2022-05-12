<?php
//$_POST['data']="incoming";
//var_dump($_POST);

if($_POST['data']=="incoming"){
	$clientCount = $_POST['count'];
	//$clientCount=6;
	$query = "SELECT * FROM orders WHERE completed=0";
	$serverCount = mysqli_num_rows(executeQuery($query));
	while($clientCount==$serverCount){
		//echo "$serverCount<br>";
		$serverCount = mysqli_num_rows(executeQuery($query));
		//$serverCount = 0;
		if($serverCount == 0){
			sleep(5);
			break;
			//echo(assembleJson(0));
			//die();
		}
		sleep(5);
	}
	echo(assembleJson($query));
}

if($_POST['data']=="completed"){
	include("classes/dbConn.php");
	//var_dump($_POST);
	$firstName = 'SUBSTRING_INDEX(TRIM(name)," ",1)';
	$lastName = 'SUBSTRING_INDEX(TRIM(name)," ",-1)';
	if(isset($_POST['sort'])){
		if($_POST['sort']=="orderNum")
			$sort = "id";
		if($_POST['sort']=="time")
			$sort = "datetime";
		if($_POST['sort']=="lastName")
			$sort = $lastName;
		if($_POST['sort']=="firstName")
			$sort = "name";
	}
	else
		$sort = "id";
	if(isset($_POST['filter'])){
		$filterArray = json_decode($_POST['filter'],true);
		$filter="";
		foreach($filterArray as $column => $value){
			if($column=="firstName")
				$column=$firstName;
			if($column=="lastName")
				$column=$lastName;
			$filter=$filter."AND $column LIKE '%$value%' "; //this may have performance issues.
		}
	}
	else
		$filter = "";
	if(isset($_POST['order']))
		$order = mysqli_real_escape_string($link,$_POST['order']);
	else
		$order = "DESC";
	if(isset($_POST['search']))
		$search = mysqli_real_escape_string($link,$_POST['search']);
	else
		$search = "ASC";
	$year = date("Y");
	$yearFilter = "SUBSTRING(datetime,1,4) = '$year'";
	$query = "SELECT * FROM orders WHERE completed=1 AND $yearFilter ";
	$query = $query.$filter;
	$query = $query."ORDER BY $sort $order ";
	//$query = $query."LIMIT 100 OFFSET $offset";
	//echo $query;
	echo(assembleJson($query));
}

function assembleJson($query){
	$returnData = array();
	$result = executeQuery($query);
	while($row = mysqli_fetch_assoc($result)){
		//echo $result;
		$name = explode(' ',$row['name']);
		$last = end($name);
		$first = implode(' ',array_slice($name,0,count($name)-1));
		$data = array('orderNum' => $row['id'], 'time' => $row['datetime'], 'lastName' => $last, 'firstName' => $first);
		array_push($returnData, $data);
	}
	return json_encode($returnData);
}

function executeQuery($query){
	include("classes/dbConn.php");
	return mysqli_query($link,$query);
}

?>



