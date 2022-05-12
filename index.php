<?php 
    session_start();
    include 'classes/dbConn.php';
    include 'infoGetter.php';

	if(isset($_POST))
		$_SESSION['g-recaptcha-response'] = $_POST['g-recaptcha-response'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <title>Order Regalia</title>

		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"> 

       <link rel="stylesheet" type="text/css" href="mobileStyle.css"/>
        <script type="text/javascript" src="scripts.js"></script>
        <script language="javascript" type="text/javascript">
			var metricShowing = false;
            //Form Validation
            // Check for valid email address
            function isEmail(elm) {
                return elm.value.search(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/) != -1;
                
            }
            // Check for Phone
            function isPhone(elm) {
                return elm.value.search(/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/) != -1;
            }

      
            // Check for valid uid
            function isUid(elm) {
                return elm.value.search(/^(u\d{7}|\d{8})$/) != -1;
            }
			
            // Check for blank fields
            function isFilled(elm) {
                if (elm.value === "" || elm.value === null || elm.value === undefined) {
                    return false;
                }
                else {
                    return true;
                }
            }
			
			//checks for valid height in feet range
			function checkHeightF(elm){
				//correct pattern should be [3-8] feet
				if((elm.value > 8 || elm.value < 3) && elm.value != ""){
					elm.value = "";
				}
			}
			
			//checks for valid height in inch range
			function checkHeightI(elm){
				if((elm.value > 11 || elm.value < 0) && elm.value != ""){
					elm.value = "";
				}
			}
			
			//checks weight
			function checkWeight(elm){
				//correct pattern should be [85-400] lbs
				if(elm.value > 400 && elm.value < 40 && elm.value != ""){
					elm.value = "";
				}
			}
			
						//checks for valid height in inch range
			function checkHeightCM(elm){
				if((elm.value > 244 || elm.value < 1) && elm.value != ""){
					elm.value = "";
				}
			}
			
			//checks weight
			function checkWeightKG(elm){
				//correct pattern should be [85-400] lbs
				if(elm.value > 181 && elm.value < 5 && elm.value != ""){
					elm.value = "";
				}
			}
			
			//hide/show div for metric
			function toggleMetric(){
				//clears previous heightWeightFields to avoid bugs
				$("#heightF").val("");
				$("#heightI").val("");
				$("#weight").val("");
				$("#heightC").val("");
				$("#weightKG").val("");
				
				//actually switches fields
				if(!metricShowing){
					$("#heightWeight").hide();
					$("#metric").show();
					setMetricShowing(true);
				}else{
					$("#metric").hide();
					$("#heightWeight").show();
					setMetricShowing(false);
				}
			}
			
			function setMetricShowing(bool){
				metricShowing = bool;
			}
				
				
			function convertToFeet(){
				//CONVERTS CM TO FEET & INCHES
				//ALSO CONVERTS KGS TO LBS
				if(metricShowing){
					var centemeters = $("#heightC").val();
					
					var inches = Math.round(centemeters/2.54);
					
					var heightInches = Math.round(inches % 12);
					
					var heightFeet = (inches - heightInches)/12;
					
					$("#heightF").val(heightFeet);
					$("#heightI").val(heightInches);
					
					//weight conversion
					var kilograms = $("#weightKG").val();
					var pounds = Math.round(kilograms * 2.205);
					
					$("#weight").val(pounds);
					
					$("#metric").hide();
					$("#heightWeight").show();
					$("#imperial").prop("checked", false);
					metricShowing=false;
				}
			}
			
			function checkEmptyHeightWeight(){
				if($("#heightF").val()==0 && $("#weight").val()==0){
					alert("Please fill in your Height and Weight.");
					if($("#field4").is(":hidden")){
						location.reload();
					}
					return false;
				}
			}
				
            // Check entire form, modified from pre2018 grad form validation
            function isReady(form) {
				
                console.log("Checking>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
                if (isFilled(form.unid) == false) {
                    alert("Please enter your UID.");
                    // form.uid.focus();
                    return false;                                        
                }

                if (isUid(form.unid) == false) {
                    alert("uID must be formatted as follows: \nu0123456 or 00123456");
                    // form.unid.focus();
                    return false;
                }
				
                if (isFilled(form.fname) == false) {
                    alert("Please enter your first name.");
                    // form.fname.focus();
                    return false;
                }

                if (isFilled(form.lname) == false) {
                    alert("Please enter your last name.");
                    // form.lname.focus();
                    return false;
                }

                if (isFilled(form.heightF) == false || $("#heightF").val() == 0) {
						alert("Please enter your Height.");
						// form.heightF.focus();
						return false;
                }
				
                if (isFilled(form.heightI) == false && isFilled(form.heightC) == false) {
					alert("Please enter your Height.");
					// form.heightI.focus();
					return false;
                }

                if (isFilled(form.weight) == false && isFilled(form.weightKG) == false || $("#weight").val() == 0) {
					alert("Please enter your weight.");
					// form.weight.focus();
					return false;
                }
                
                if (isFilled(form.degreeType) == false) {
                 alert("Please select your Degree Type from the pull down menu.");
                 // form.degreeType.focus();
                 return false;
                }
                
					
               	if (isFilled(form.college) == false) {
                    alert("Please select your College from the pull down menu.");
                    // form.college.focus();
                    return false;
                }
                if (form.college.value == "Medicine") {
                    if (isFilled(form.fieldOfStudy) == false) {
                        alert("Please select your Field of Study from the pull down menu.");
                        // form.college.focus();
                        return false;
                    }
                }
				
				checkEmptyHeightWeight();

                if (form.bachelorpkg.selectedIndex < 1 &&
                     form.masterpkg.selectedIndex < 1 &&
                     form.phdpkg.selectedIndex < 1){
                    var itemList = document.getElementsByName("selectedItems[]");
                    var checked = false;
                    for (i = 0; i < itemList.length; i++){
                        if(itemList[i].checked == true){    
                            checked = true;
                        }
                    }
                    if (checked == false){
                        alert("Either you must select a commencement package or \nselect at least one individual item.");
                        return false;
                    }
					
					checkWeightHeightEntered();
                }

		        //FOR ALUMNI
				
                //if (form.join_alumni[0].checked){
                    
                    //if (! isFilled(form.email) || ! isEmail(form.email)){
                        //alert('Enter a valid Email');
                        // form.email.focus();
                      //  return false;
                    //}
                    
                    //if (! isFilled(form.phone) || ! isPhone(form.phone)){
                      //  alert('Enter a valid Phone Number');
                        // form.phone.focus();
                    //    return false;
                  //  }
                    // Address not required
                //}
                    
                
                return true;
            }


                // *********************************************************************************************

            var selectIDs = ["bachelorpkg","masterpkg","phdpkg"]; // This array is used inside the function enforceOnePkgLimit
            var images = ["images/bachelor1.jpg","images/masters1.jpg", "images/phd1.jpg", 
                            "images/bachelor2.jpg", "images/masters2.jpg", "images/phd2.jpg"];
            function enforceOnePkgLimit(source) {
                alertPackageContents(source);

                // Clear out all of the selects (so that only one at a time may hold a value) & update images
                for (var i = 0; i < 3; i++) {
                    if(i != source) {
                        document.getElementById(selectIDs[i]).selectedIndex = 0;
                    }
                    else { // If it's the selected degree type, display corresponding images from array above
                        $("#pkgImg1").attr("src",images[i]);
                        $("#pkgImg2").attr("src",images[i + 3]);
                    }
                }
            }
            // *********************************************************************************************
		
            // Auto-update qty to 1 if an individual item is checked
            function updateQuantities(input){
                alertDuplicateItem(input);

                // substring after "item" until the end
                var idNum = input.substring(4);
                var qtyID = "qty" + idNum;

                if ($("#"+input).is(':checked') == true) {
                    $("#"+qtyID).val(1);
                }
                else {
                    $("#"+qtyID).val(0);
                    // console.log("Testqty2");
                    //call updateNameByQty()?
                }
            }

            // Update quantity in qty[itemID][quantity] name array 
            // Seek a more elegant approach...
            // Server side: only quantities for selected items are recorded in db
            function updateNameByQty(qtyElement){
                var id = qtyElement.id;
                var newVal = qtyElement.value;
                var index = $("#"+id).attr("name").indexOf("][") + 2; // Interior of 2nd brackets
                var oldName = $("#"+id).attr("name").substring(0,index);
                var newName = oldName + newVal + "]";

            }

            function adjustUID(uidElement) {
              var val = uidElement.value;
              if (val.charAt(0) == "U") {
              var nums = val.substring(1);
              $('#unid').val("u" + nums);
              }
            }

            // Called by enforceOnePkgLimit
            function alertPackageContents(){
                //alert("The package you selected includes a cap, gown, tassel (and stole if indicated). ");
            }
           
            // Called by updateQuantities
            function alertDuplicateItem(thisID){
                //If package was selected
                if ($("#bachelorpkg").val() != '' || $("#masterpkg").val() != '' || $("#phdpkg").val() != '') {
                    var name = $("#" + thisID).attr("placeholder");
                    if ($("#" + thisID).is(':checked')) { //Don't check on de-select
                        if (name.indexOf("Cap") != -1) {
                            alert("The package you selected already contains a cap. Are you sure you want to order an additional cap?");
                        }
                        else if (name.indexOf("Gown") != -1) {
                            alert("The package you selected already contains a gown. Are you sure you want to order an additional gown?");
                        }
						else if (name.indexOf("Tassel") != -1) {
                            alert("The package you selected already contains a tassel. Are you sure you want to order an additional tassel?");
                        }
						else if (name.indexOf("Stole") != -1 && ($("#bachelorpkg").val().indexOf("stole") != -1|| $("#masterpkg").val().indexOf("stole") != -1|| $("#phdpkg").val().indexOf("stole") != -1)) {
                            alert("The package you selected already contains a stole. Are you sure you want to order an additional stole?");
                        }
                    }
                }
            }
			
        </script>
    </head>
    <body onbeforeunload="return true" >
		<p id="debug"> </p>
	
			<!--**************************************************************************************************-->
            <div class="container" id="progress-field-wrapper" >
			<form method="post" id="mainform" onsubmit="return isReady(this);" action="robot_check.php">
			<div class="card text-center">
				<!-- progressbar -->
				<div class="card-header">
					<ul id="progressbar" class="nav nav-pills card-header-pills">
						<li class="nav-item active" id="fs1">
							<a class="nav-link">Your Info</a>
						</li>
						<li id="fs2" class="nav-item">
							<a class="nav-link">Package Options</a>
						</li>
						<li id="fs3" class="nav-item">
							<a class="nav-link">Item Options</a>
						</li>
						<li id="fs4" class="nav-item" onclick="convertToFeet()">
							<a class="nav-link">Review</a>
						</li>
					</ul>
					<div class="progress" id="progressTracker">
						<div class="progress-bar" id="progressMarker"></div>
					</div>
				</div>

                <!-- fieldsets -->
                <div id="elementDiv">
                  <!-- ===========================================Enter your information:=========================================== -->
                    <fieldset id="field1">
                        <h2 class="fs-title">Enter Your Information</h2>
                        <h3 class="fs-subtitle">All fields are required unless indicated otherwise.</h3>
                        <input type="text" pattern="[u].{5,}" id="unid" name="unid" placeholder="uNID (u1234567)" tabindex="1" oninput="adjustUID(this)">
                        <!-- pattern ="(^\d{7}$)"  -->
                        <input type="text" id="fname" name="fname" placeholder="First Name" tabindex="1"/>

                        <input type="text" id="lname" name="lname" placeholder="Last Name" tabindex="1"/><br><br>
						
						
						<div id="metric" style="display:none">
							<span>
								<label for="heightC">Height:</label>
								<input id="heightC" name="heightC" placeholder="cm" style="width: 28% !important;" oninput="checkHeightCM(this)" tabindex="1" inputmode="numeric"/> cm
							</span><br>
							<span>
								<label for="weightKG">Weight:</label>
								<input id="weightKG" name="weightKG" placeholder="kg" oninput="checkWeightKG(this)" style="width: 27.5% !important;" tabindex="1" inputmode="numeric"/> kg
							</span><br><br>
						</div> <!-- end metric -->
						
                        <div id="heightWeight" style="position:relative;">
							<span>
								<label for="heightF">Height:</label>
								<input id="heightF" name="heightF" placeholder="Feet" style="width: 28% !important;" tabindex="1" oninput="checkHeightF(this)" inputmode="numeric"/><strong>'</strong><input id="heightI" name="heightI" placeholder="Inches" style="width: 28% !important;" oninput="checkHeightI(this)" tabindex="1" inputmode="numeric"/><strong>"</strong>
							</span><br>
							<span>
								<label for="weight">Weight:</label>
								<input id="weight" name="weight" placeholder="Weight" oninput="checkWeight(this)" style="width: 30% !important;" tabindex="1" inputmode="numeric"/>lbs.
							</span>
						</div> <!-- end imperial -->

						<div class="smallWidth">
							<input type="checkbox" name="imperial" id="imperial" oninput="toggleMetric()">
							<label for="imperial">Use Metric Measurements</label>
							<br>
							<br>
						</div>
					
                        <select id="degreeType" name="degreeType" tabindex="1" >
                              <option value ='' selected disabled>Degree</option>
                              <option value ='Bachelor' >Bachelors</option>
                              <option value ='Master' >Masters</option>
                              <option value ='Doctorate' >Doctorate</option>
                        </select>

                        <select id="college" name="college" tabindex="1" onchange="checkForMedicine()" >
                            <option value ='' selected disabled>College</option>
                            <?php
                                foreach ($colleges as $college) {
                                    echo '<option value="' . $college['name'] . '"  >'. $college['name'] . '</option>';
                                }
                            ?>
                        </select>

                                                
                        <ul id="medFieldStudy" name="medFieldStudy" style="display: none;">Field of Study (medical students only):
                            <select id="fieldOfStudy" name="fieldOfStudy" tabindex="1">
                                <option value="" selected></option>
                                <option value="Biochemistry">Biochemistry</option>
                                <option value="Biomedical Informatics">Biomedical Informatics</option>
                                <option value="Biostatistics">Biostatistics</option>
                                <option value="Clinical Investigation">Clinical Investigation</option>
                                <option value="Doctor of Medicine">Doctor of Medicine</option>
                                <option value="Experimental Pathology">Experimental Pathology</option>
                                <option value="Genetic Counseling">Genetic Counseling</option>
                                <option value="Human Genetics">Human Genetics</option>
                                <option value="Laboratory Medicine &amp; Biomedical Science">Laboratory Medicine &amp; Biomedical Science</option>
                                <option value="Medical Informatics">Medical Informatics</option>
                                <option value="Medical Laboratory Science/Cytotechnology">Medical Laboratory Science/Cytotechnology</option>
                                <option value="Neurobiology &amp; Anatomy">Neurobiology &amp; Anatomy</option>
                                <option value="Neuroscience">Neuroscience</option>
                                <option value="Occupational Health">Occupational Health</option>
                                <option value="Oncological Sciences">Oncological Sciences</option>
                                <option value="Pathology">Pathology</option>
                                <option value="Physiology">Physiology</option>
                                <option value="Physician Assistant Studies">Physician Assistant Studies</option>
                                <option value="Public Health/Health Science Administration">Public Health/Health Science Administration</option>
                            </select>
                        </ul>
						<br>
                        <input type="button" name="next" class="next action-button" value="Next" tabindex="1"/>
                    </fieldset>
                    <!-- =============================================Package Options:========================================= -->
                    <fieldset id="field2">

                        <h2 class="fs-title">Package Options</h2>
                        <h3 class="fs-subtitle">Select <strong>one</strong> package below. If you would prefer to choose individual items instead, you may do so on the next page.</h3>
                        <div id="packages"></div>
                        <span>Bachelors
                        <select name="bachelorpkg" id="bachelorpkg" tabindex="4" onchange="enforceOnePkgLimit(0);" tabindex="2" >
                            <option value ='' selected>--Select--</option>
                            <?php
                                foreach ($bachelor_pkg as $pkg) {
                                    echo '<option value="' . $pkg['name'] . '" label="' . $pkg['name'] . ' - ' . $pkg['price'] . '">' . $pkg['name'] . ' - ' . $pkg['price'] . '</option>';
                                }
                            ?>
                        </select>
                        </span><br>
                        <span>Masters&nbsp&nbsp&nbsp&nbsp
                        <select name="masterpkg" id="masterpkg" tabindex="4" onchange="enforceOnePkgLimit(1);" tabindex="2">
                            <option value ='' selected>--Select--</option>
                            <?php
                                foreach ($master_pkg as $pkg) {
                                    echo '<option value="' . $pkg['name'] . '" label="' . $pkg['name'] . ' - ' . $pkg['price'] . '">' . $pkg['name'] . ' - ' . $pkg['price'] . '</option>';
                                }
                            ?>  
                        </select>
                        </span><br>
                        <span>Doctorate
                        <select name="phdpkg"  id="phdpkg" tabindex="4" onchange="enforceOnePkgLimit(2);" tabindex="2" >
                            <option value ='' selected>--Select--</option>
                             <?php
                                foreach ($phd_pkg as $pkg) {
                                    echo '<option value="' . $pkg['name'] . '" label="' . $pkg['name'] . ' - ' . $pkg['price'] . '">' . $pkg['name'] . ' - ' . $pkg['price'] . '</option>';

                                }
                            ?>
                        </select>
                        </span> 
                        <br><!--<br>-->
                        <?php

                        // Set in script above
                        echo '<span><img id="pkgImg1" src="images/'.(isset($pic1)?$pic1:"bachelor1.jpg").'" alt="Graduation Packages" width="240">&nbsp<img id="pkgImg2" src="images/'.(isset($pic2)?$pic2:"bachelor2.jpg").'" alt="Graduation Packages" width="240"></span>';
                        ?>
                        <br><!--<br><br>-->
                            <input type="button" name="previous" class="previous action-button" value="Previous" tabindex="2"/>
                            <input type="button" name="next" class="next action-button" value="Next" onclick="convertToFeet()" tabindex="2"/>
                    </fieldset>
                    <!-- ==========================================Optional items:===================================== -->
                    <fieldset id="field3">
                        <h2 class="fs-title">Individual Item Options</h2>
                        <h3 class="fs-subtitle">If you already selected a package, you do not need to duplicate your order here, but you may select extra items as souvenirs. <br> If you did not select a package, you must choose your cap, gown, and tassel here.</h3>
						<table id="indivItemTable">
							<th>&nbsp</th>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty.</th>
                          
							<?php foreach ($items as $item): ?>
								<tr>
									<td>
									<input type="checkbox" id="item<?php echo $item['sku']; ?>" name="selectedItems[]" value="<?php echo $item['sku']; ?>"  placeholder="<?php echo $item['name'].' - '.$item['price'];?>" style="width: 30px;" onclick="updateQuantities(this.id)">
									</td>
									<td class="Item">
										<?php echo $item['name']; ?>
									</td>
									<td><?php echo $item['price']; ?></td>
									<td class="Qty">
										<input  id="qty<?php echo $item['sku']; ?>" name="qty[<?php echo $item['sku']; ?>][ ]" value=0 pattern="^\d{1,3}$" onchange="updateNameByQty(this)" inputmode="numeric">
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
<br>
                       
                        <input type="button" name="previous" class="previous action-button" value="Previous" tabindex="2"/>
						<input type="button" name="next" class="next action-button" onclick="updateOrderReview()" value="Next" tabindex="2"/>
<br>
                    </fieldset>
                                        <!-- ======================================Review and print your order:======================================== -->
                    <fieldset id="field4">
                        <h2 class="fs-title">Review and Print Your Order</h2>
                        <h3 class="fs-subtitle">Please go back and edit any incorrect information before printing.</h3>
                        <div id="revOrderDiv">
                            <div id="rev_unid"></div>
                            <div id="rev_name"></div> 
                            <div id="rev_height"></div> 
                            <div id="rev_weight"></div>
                            <div id="rev_degreeType"></div>
                            <div id="rev_college"></div>
                            <!-- <div id="rev_date"></div> -->
                            <div id="rev_fieldOfStudy"></div> 
                            <div id="rev_alumni"></div>
                            <!--<div id="rev_alumni_contact"></div>-->
                            <div id="rev_package"></div>
                            <div id="rev_indivItems"></div>
						</div>
<br>
<h2>Email for order confirmation: </h2>

						<input type="email" name="email" placeholder="unid@utah.edu" required>
						<div class="g-recaptcha" data-sitekey="SITEKEY" data-size="compact" style="margin:auto; width:164px;"></div>       
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
						<input type="submit" onclick="$('body').attr('onbeforeunload','')" name="submit" class="submit action-button" value="Submit" />

					</fieldset>
                
                </div> <!-- end fieldset element -->
		  </form>
	</div> <!-- end progress-field-wrapper div -->

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>
