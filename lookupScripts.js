function ajaxRequestIncoming(params){
	clientRows = $('#incomingTable').bootstrapTable('getOptions').totalRows;
	//console.log(clientRows);
	$.post("serveOrders.php",  {'data': 'incoming', 'count': clientRows}, function(data){
		//console.log("Serving");
		if(data==[]){
			console.log("Data 0");
			$('#incomingTable').bootstrapTable('removeAll');
			$('#incomingTable').bootstrapTable('hideLoading');
			return;
		}
		params.success({
			"rows": data,
			"total": data.length
		}); ajaxRequestIncoming(params);
		}, "json");
}

function ajaxRequestCompleted(params){
	console.log(params.data);
	postParams = $.extend(params.data,{"data":"completed"});
	console.log(postParams);
	$.post("serveOrders.php", postParams, function(data){
		params.success({
			"rows": data,
			"total": data.length
		});
		}, "json");

}

function toggleCompleted(value){
	$('#completedTableWrapper').toggle();
	if(value=="show"){
		$('.completedToggle > span').addClass('fa-chevron-up').removeClass('fa-chevron-down');
		$('.completedToggle').attr("onclick","toggleCompleted('hide')");
	}
	else{
		$('.completedToggle > span').addClass('fa-chevron-down').removeClass('fa-chevron-up');
		$('.completedToggle').attr("onclick","toggleCompleted('show')");	
	}
}

