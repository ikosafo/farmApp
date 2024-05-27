<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getUser = $mysqli->query("select * from users where uId = '$i_id'");
$resUser = $getUser->fetch_assoc();

?>

<form autocomplete="off" id="addUserForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Edit System Users </h5>
        
		
		 <div class="row mt-3">
            <div class="col-12 col-sm-4">
               <label for="fullName">Full Name</label>
                <input id="fullName" class="form-control" type="text" name="fullName" disabled placeholder="Enter full name" value="<?php echo $resUser['fullName']; ?>">
            </div>
            
			
			
			<div class="col-12 col-sm-2">
                 <label for="phoneNumber">Phone Number</label>
                <input id="phoneNumber" class="form-control" type="tel" disabled onkeypress="return isNumber(event)" name="phoneNumber" placeholder="Enter phone number" value="<?php echo $resUser['phoneNumber']; ?>">
            </div>
			
			<div class="col-12 col-sm-2">
               <label for="dateOfBirth">Date of Birth</label>
                <input id="dateOfBirth" class="form-control" type="date" disabled name="dateOfBirth" placeholder="Enter date of birth" required value="<?php echo $resUser['dob']; ?>">
            </div>
			
			<div class="col-12 col-sm-2">
			<label for="address">Home Address</label>
			<input id="address" class="form-control" type="tel" disabled name="address" placeholder="Enter address" value="<?php echo $resUser['address']; ?>">	
				
            </div>
			
			<div class="col-12 col-sm-2">
			<label for="email">Email Address</label>
			<input id="email" class="form-control" type="email" disabled name="email" placeholder="Enter email address" required value="<?php echo $resUser['emailAddress']; ?>">
				
            </div>
        </div>
		
		
		
        <div class="row mt-3">
         <div class="col-12 col-sm-3">
       <label for="username">Username</label>
                <input id="username" class="form-control" type="text" disabled name="username" placeholder="Enter username" required value="<?php echo $resUser['userName']; ?>">      
         </div>
		 
         <div class="col-12 col-sm-3 mt-3 mt-sm-0">
         <label for="password">Password</label>
         <input id="password" class="form-control" type="password" disabled name="password" disabled required>
         </div>
		 
		<div class="col-12 col-sm-3 mt-3 mt-sm-0">
		<label for="permissions">Permissions</label>
		<select id="permissions" class="form-select" name="permissions" disabled required>
			<option value="">Select Permissions</option>
			<option <?php if ($resUser['permission'] == "Financial Management") echo "selected" ?>>Financial Management</option>
			<option <?php if ($resUser['permission'] == "Equipment Management") echo "selected" ?>>Equipment Management</option>
			<option <?php if ($resUser['permission'] == "Field Management") echo "selected" ?>>Field Management</option>
			<option <?php if ($resUser['permission'] == "User Management") echo "selected" ?>>User Management</option>
		</select>
		</div>
		 
		 <div class="col-12 col-sm-3 mt-3 mt-sm-0">
        <label for="role">Role</label>
                <select id="role" class="form-select" disabled name="role" required>
                    <option value="">Select Role</option>
                    <option <?php if (strtolower($resUser['role']) == "admin") echo "selected" ?>>Admin</option>
                    <option <?php if (strtolower($resUser['role'])  == "user") echo "selected" ?>>User</option>
                </select>
         </div>
		 
        </div>

		<hr>
		
        <div class="row mt-3">
         <div class="col-12 col-sm-12">
            
         </div>
		 
         <div class="col-12 col-sm-4 mt-3 mt-sm-0">
         
         </div>
		 
		<div class="col-12 col-sm-4 mt-3 mt-sm-0">
        <button  style="width: 80% !important; float: left; margin-top: 0px;" id="addUserBtn" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Save Record">SAVE RECORD >> </button>    
		 </div>
		 
        </div>
        

       
    </div>
</form>






<script>
    $("#addUserBtn").click(function() {
        loadPage("ajaxscripts/forms/addUser.php", function(response) {
            $('#pageForm').html(response);
        });
    });
</script>