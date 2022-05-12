var animating = false; //flag to prevent quick multi-click glitches
$(document).ready(function () {
	var current_fs, next_fs, previous_fs; //fieldsets
	var left, opacity, scale; //fieldset properties which we will animate
	
	$(".next").click(function(){
		if(getAnimating()) return false;
		setAnimating(true);
		
		current_fs = $(this).parent();
		next_fs = $(this).parent().next();
		
		//activate next step on progressbar using the index of next_fs
		$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
		
		//show the next fieldset
		next_fs.show(); 
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now, mx) {
				//as the opacity of current_fs reduces to 0 - stored in "now"
				//increase opacity of next_fs to 1 as it moves in
				opacity = 1 - now;
				current_fs.css({
	        'position': 'absolute'
	      });
				next_fs.css({'opacity': opacity});
			}, 
			duration: 800, 
			complete: function(){
				current_fs.hide();
				setAnimating(false);
			}, 
			//this comes from the custom easing plugin
			easing: 'easeInOutBack'	
		});

		//updates the "connector" progress bar between steps
		//Selects the fieldset ID, then gets the 6th character (stepNum)
		thisStep = $(this).parent().attr('id')[5];
		$("#progressMarker").css("width",(25*(thisStep)+5).toString()+"%");
	});



	$(".previous").click(function(){
		if(getAnimating()) return false;
		setAnimating(true);
		
		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();
		
		//de-activate current step on progressbar
		$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
		
		//show the previous fieldset
		previous_fs.show(); 
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now, mx) {
				//as the opacity of current_fs reduces to 0 - stored in "now"
				// increase opacity of previous_fs to 1 as it moves in
				opacity = 1 - now;
				previous_fs.css({'opacity': opacity});

			}, 
			duration: 800, 
			complete: function(){
				current_fs.hide();
				setAnimating(false);
			}, 
			//this comes from the custom easing plugin
			easing: 'easeInOutBack'
		});
		//updates the "connector" progress bar between steps
		//Selects the fieldset ID, then gets the 6th character (stepNum)
		thisStep = $(this).parent().attr('id')[5];
		$("#progressMarker").css("width",(25*(thisStep-2)+5).toString()+"%");
	});
	
	checkClick(1);
	checkClick(2);
	checkClick(3);
	checkClick(4);
	
});

//Should be 4 without alumni
var numOfPages = 4;

var heightF = null, heightI = null, weight = null;
var reachedLastPage = false;

function setAnimating(bool){
	animating = bool;
}

function getAnimating(){
	return animating;
}

function getActiveFieldset(){
	for(var i = 1; i < numOfPages+1; i++){
		if($("#field"+i).is(":visible")){
			return $("#field"+i);
		}
	}
	return $("#field1");
}

function checkClick(num){
	if(!getAnimating()){
		$("#fs"+num).click(function(){
			//makes sure you can't transition into the page you're already on
			if($("#field"+num).is(":hidden")){
				setAnimating(true);
				
				//gets the id of current Fieldset
				current_fs = getActiveFieldset();
				//fieldset to be transitioned onto
				previous_fs = $("#field"+num);
				
				//updates the progressbar
				setProgressBar(num);
				
				//show the new fieldset
				//previous_fs.show(); 
				
				//hide the current fieldset with style
				current_fs.animate({opacity: 0}, {
					step: function(now, mx) {
						//as the opacity of current_fs reduces to 0 - stored in "now"
						// increase opacity of previous_fs to 1 as it moves in
						opacity = 1 - now;
						previous_fs.css({'opacity': opacity});

					}, 
					duration: 400, 
					complete: function(){
						//calls this method to fix multi field bug
						hideFields(num);
						setAnimating(false);
						
						//show the new fieldset
						previous_fs.show();
					}, 
					//this comes from the custom easing plugin
					easing: 'easeInOutBack'
				});
				heightWeightRefresh();

			}
		});
	}
}

function hideFields(num){
	for(var i = 1; i < numOfPages+1; i++){
			$("#field"+i).hide();
	}
	$("#field"+num).show();
}

function setProgressBar(num){
	var i = num-1;
	
	//defaults to 1
	$("#progressbar li").eq(1).removeClass("active");
	$("#progressbar li").eq(2).removeClass("active");
	$("#progressbar li").eq(3).removeClass("active");
	
	//activates ones that are less than num
	for(i; i>0; i--){
		$("#progressbar li").eq(i).addClass("active");
	}

	//updates the "connector" progress bar between steps
	$("#progressMarker").css("width",(25*(num-1)+5).toString()+"%");
}

function heightWeightRefresh(){
	if(reachedLastPage){
		if(heightF != null && heightI != null && weight != null){
		//bug fix for when someone clicks the metric conversion too many times
		$('#heightF').val(heightF);
		$('#heightI').val(heightI);
		$('#weight').val(weight);
		}
	}
}


// --------------------------------------------

function checkForMedicine() {
	if ($('#college')[0].value == "Medicine") {
		$('#medFieldStudy').show();
		$('#fieldOfStudy').show();  // Adjustment for Order Review back-and-forth
	} else {
		$('#medFieldStudy').hide();
		$('#fieldOfStudy').hide();  // Adjustment for Order Review back-and-forth

	}
}




// Get the value of each input field and conveniently display them for review
function updateOrderReview(){
	reachedLastPage = true;
	// ----- USER INFO ---- //
	var unid = $('#unid').val();
	$('#rev_unid').html("<b>uNID:</b> " + unid);

	var fname = $('#fname').val();
	var lname = $('#lname').val();
	$('#rev_name').html("<b>Name:</b> " + fname + " " + lname);

	heightF = $('#heightF').val();
	heightI = $('#heightI').val();
	$('#rev_height').html("<b>Height:</b> " + heightF + "\' " + heightI + "\"");

	weight = $('#weight').val();
	$('#rev_weight').html("<b>Weight:</b> " + weight + " lbs.");
	
	var degree = $('#degreeType').val();
	$('#rev_degreeType').html("<b>Degree:</b> " + degree);

	var college = $('#college').val();
	$('#rev_college').html("<b>College:</b> " + college);

	// var date = $('#date').val();
	// $('#rev_date').html("<b>Date:</b> " + date);

	if ($.trim($('#fieldOfStudy').val()) != ''){
		var fieldOfStudy = $('#fieldOfStudy').val();
		$('#rev_fieldOfStudy').html("<b>Field of Study:</b> " + fieldOfStudy);
	}

	// --- Alumni Response ---//
	//var alumniRespose = (document.getElementById('join_alumni_yes').checked == true) ? "Yes" : "No";
	//$('#rev_alumni').html("<b>Alumni Offer:</b> " + alumniRespose);
	//if (document.getElementById('join_alumni_yes').checked == true) {
		//var alumniPhone = $('#phone').val();
		//var alumniEmail = $('#email').val();
		//var alumniAddress = $('#address').val();
		//$('#rev_alumni_contact').html("<b>Alumni Contact:</b> " + "<br>" +
			//alumniPhone + "<br>" + alumniEmail + "<br>" + alumniAddress);
	//}	

	// ------- REGALIA ------ // 
	if($.trim($('#bachelorpkg').val()) != ''){
		var bachelorpkg = $('#bachelorpkg').val();
		$('#rev_package').html("<b>Package:</b> " + bachelorpkg);
	}	
	else if($.trim($('#masterpkg').val()) != ''){
		var masterpkg = $('#masterpkg').val();
		$('#rev_package').html("<b>Package:</b> " + masterpkg);
	}
	else if($.trim($('#phdpkg').val()) != ''){
		var phdpkg = $('#phdpkg').val();
		$('#rev_package').html("Package: " + phdpkg);
	}
	else { $('#rev_package').html("<b>Package:</b> None" ); }

	// --- Individual Items ---//
	var itemList = document.getElementsByName("selectedItems[]");
		$('#rev_indivItems').html("<b>Individual Items:</b> <br>" ); 
		var checked = false;
		for (i = 0; i < itemList.length; i++){
			if(itemList[i].checked == true){	
				checked = true;
				// "item" was added to these ids in html incase id concept wanted to be used elsewhere
				var idNum = String(itemList[i].value);
				var itemID = "item" + idNum; 	 
				var itemName = $("#"+itemID+"").attr('placeholder'); // Placeholder stores the name
				var qtyID = "qty" + idNum;
				var qtyVal = $("#"+qtyID).val();
				$('#rev_indivItems').append(itemName + " x " + qtyVal + "<br>" );
			}
		}
		if (checked == false){
			$('#rev_indivItems').html("<b>Individual Items:</b> None" );
		}


}

