<?php
?>
<!DOCTYPE html>
<html>
<head>
	<title>Lookup Mobile Regalia Orders</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"> 
	<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.2/dist/bootstrap-table.min.css">
	<link rel="stylesheet" type="text/css" href="extensions/filter-control/bootstrap-table-filter-control.css">
	<link rel="stylesheet" type="text/css" href="mobileStyle.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

	<script>
		$(document).ready(function(){
			$('#incomingTable').bootstrapTable({
				onClickRow: function(e){
					console.log(e.orderNum);
					$('#order_id').val(e.orderNum);
					$('#lookupForm').submit();
				}
			});
			$('#completedTable').bootstrapTable({
				onClickRow: function(e){
					console.log(e.orderNum);
					$('#order_id').val(e.orderNum);
					$('#lookupForm').submit();
				}
			});

		})
	</script>

</head>

<body>
<br>
<div class="container">
<div class="card">
	<div class="text-center card-header text-header">Order Lookup</div>
	<div style="margin-left:10px">
		<h1>Enter Order Number:</h1>
		<br>
		<form method="POST" action="print.php" id="lookupForm">
			<div class="form-group row align-items-center">
				<div class="col-auto">
					<input id="order_id" class="form-control mb-2" type="text" name="order_id" autofocus>
				</div>
				<div class="col-auto">
					<button type="submit" class="btn btn-danger mb-2">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
<br>

<div class="card">
	<div class="text-center card-header text-header">
		Incoming orders
		<button class="btn btn-primary cardOptions" onclick="$('#incomingTable').bootstrapTable('refresh');"><span class="fa fa-refresh"></span></button>
	</div>
	<div id="incomingTableWrapper">
		<table id="incomingTable" class="table table-striped table-bordered table-sm" 
			data-toggle="table" data-height="460" data-filter-control="true" data-search-on-enter-key="true"
			data-ajax="ajaxRequestIncoming" >
			<thead>
				<tr>
					<th data-field="orderNum" data-sortable="true" data-filter-control="input">Order Number</th>
					<th data-field="time" data-sortable="true" data-filter-control="input">Time</th>
					<th data-field="lastName" data-sortable="true" data-filter-control="input">Last Name</th>
					<th data-field="firstName" data-sortable="true" data-filter-control="input">First Name</th>
				</tr>
			</thead>
			<tbody>
<!-- To be filled in by AJAX request -->
			</tbody>
		</table>
	</div>
</div>
<br>

<div class="card">
	<div class="text-center card-header text-header">
		Completed orders
		<button class="btn btn-primary cardOptions completedToggle" onclick="toggleCompleted('show')"><span class="fa fa-chevron-down"></span></button>
		<button class="btn btn-primary cardOptions" onclick="$('#completedTable').bootstrapTable('refresh');"style="margin-right:10px;"><span class="fa fa-refresh" ></span></button>
	</div>
	<div id="completedTableWrapper" style="display:none;">
		<table id="completedTable" class="table table-striped table-bordered table-sm" 
			data-toggle="table" data-height="460" data-filter-control="true"
			data-ajax="ajaxRequestCompleted" data-pagination="true" data-side-pagination="client" 
			data-search-on-enter-key="true">
			<thead>
				<tr>
					<th data-field="orderNum" data-sortable="true" data-filter-control="input">Order Number</th>
					<th data-field="time" data-sortable="true" data-filter-control="input">Time</th>
					<th data-field="lastName" data-sortable="true" data-filter-control="input">Last Name</th>
					<th data-field="firstName" data-sortable="true" data-filter-control="input">First Name</th>
				</tr>
			</thead>
			<tbody>
	<!-- To be filled in by AJAX request -->
			</tbody>
		</table>
	</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script type="text/javascript" src="lookupScripts.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.2/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.2/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
</body>

</html>
