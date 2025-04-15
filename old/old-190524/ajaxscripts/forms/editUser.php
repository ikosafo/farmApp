<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getUser = $mysqli->query("select * from users where uId = '$i_id'");
$resUser = $getUser->fetch_assoc();

?>
<form autocomplete="off" id="addUserForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Edit User</h5>
        <p class="mb-0 text-sm">Edit user details</p>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="fullName">Full Name</label>
                <input id="fullName" class="form-control" type="text" name="fullName" placeholder="Enter full name" value="<?php echo $resUser['fullName']; ?>">
            </div>
            <div class="col-12 col-sm-6 ">
                <label for="phoneNumber">Phone Number</label>
                <input id="phoneNumber" class="form-control" type="tel" onkeypress="return isNumber(event)" name="phoneNumber" placeholder="Enter phone number" value="<?php echo $resUser['phoneNumber']; ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="username">Username</label>
                <input id="username" class="form-control" type="text" name="username" placeholder="Enter username" required value="<?php echo $resUser['userName']; ?>">
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="password">Password</label>
                <input id="password" class="form-control" type="password" name="password" disabled required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="role">Role</label>
                <select id="role" class="form-select" name="role" required>
                    <option value="">Select Role</option>
                    <option <?php if (strtolower($resUser['role']) == "admin") echo "selected" ?>>Admin</option>
                    <option <?php if (strtolower($resUser['role'])  == "user") echo "selected" ?>>User</option>
                </select>

            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="dateOfBirth">Date of Birth</label>
                <input id="dateOfBirth" class="form-control" type="date" name="dateOfBirth" placeholder="Enter date of birth" required value="<?php echo $resUser['dob']; ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <label for="address">Address</label>
                <textarea id="address" class="form-control" name="address" rows="3" placeholder="Enter address"><?php echo $resUser['address']; ?></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="email">Email Address</label>
                <input id="email" class="form-control" type="email" name="email" placeholder="Enter email address" required value="<?php echo $resUser['emailAddress']; ?>">
            </div>

            <div class="col-12 col-sm-6 ">
                <label for="permissions">Permissions</label>
                <select id="permissions" class="form-select" name="permissions" required>
                    <option value="">Select Permissions</option>
                    <option <?php if ($resUser['permission'] == "Financial Management") echo "selected" ?>>Financial Management</option>
                    <option <?php if ($resUser['permission'] == "Equipment Management") echo "selected" ?>>Equipment Management</option>
                    <option <?php if ($resUser['permission'] == "Field Management") echo "selected" ?>>Field Management</option>
                    <option <?php if ($resUser['permission'] == "User Management") echo "selected" ?>>User Management</option>
                </select>
            </div>
        </div>

        <div class="button-row d-flex justify-content-center mt-4">
            <button id="editUser" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Submit">Update User</button>
        </div>

    </div>
</form>


<script>
    $("#editUser").click(function() {
        var formData = {
            fullName: $("#fullName").val(),
            email: $("#email").val(),
            username: $("#username").val(),
            role: $("#role").val(),
            dateOfBirth: $("#dateOfBirth").val(),
            address: $("#address").val(),
            phoneNumber: $("#phoneNumber").val(),
            permissions: $("#permissions").val(),
            userId: '<?php echo $i_id; ?>'
        };

        var url = "ajaxscripts/queries/editUser.php";

        var successCallback = function(response) {
            console.log(response);
            //alert(response);
            if (response == 'Success') {
                $.notify("User added successfully", "success");
                loadPage("ajaxscripts/forms/addUser.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/users.php", function(response) {
                    $('#pageTable').html(response);
                });
            } else {
                alert(response);
            }
        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.fullName) {
                error += 'Please enter full name\n';
                $("#fullName").focus();
            }
            if (!formData.username) {
                error += 'Please enter username\n';
                $("#username").focus();
            }
            if (!formData.role) {
                error += 'Please select role\n';
                $("#role").focus();
            }
            if (!formData.dateOfBirth) {
                error += 'Please enter date of birth\n';
                $("#dateOfBirth").focus();
            }
            if (!formData.permissions) {
                error += 'Please select permissions\n';
                $("#permissions").focus();
            }
            // Validate email if provided
            if (formData.email && !validateEmail(formData.email)) {
                error += 'Please enter a valid email address\n';
                $("#email").focus();
            }

            // You can add more validation rules as needed

            return error;
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback, validateForm);
    });

    // Function to validate email format
    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }
</script>