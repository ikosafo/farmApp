<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getUser = $mysqli->query("select * from users where uId = '$i_id'");
$resUser = $getUser->fetch_assoc();

?>
<form autocomplete="off" id="addUserForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">View User</h5>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="fullName">Full Name</label>
                <input id="fullName" class="form-control" type="text" name="fullName" placeholder="Enter full name" disabled value="<?php echo $resUser['fullName']; ?>">
            </div>
            <div class="col-12 col-sm-6 ">
                <label for="phoneNumber">Phone Number</label>
                <input id="phoneNumber" class="form-control" type="tel" onkeypress="return isNumber(event)" name="phoneNumber" placeholder="Enter phone number" disabled value="<?php echo $resUser['phoneNumber']; ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="username">Username</label>
                <input id="username" class="form-control" type="text" name="username" placeholder="Enter username" required disabled value="<?php echo $resUser['userName']; ?>">
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="password">Password</label>
                <input id="password" class="form-control" type="password" name="password" disabled required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="role">Role</label>
                <input id="role" class="form-control" type="text" name="role" placeholder="Enter role" required disabled value="<?php echo $resUser['role']; ?>">

            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="dateOfBirth">Date of Birth</label>
                <input id="dateOfBirth" class="form-control" type="date" name="dateOfBirth" placeholder="Enter date of birth" required disabled value="<?php echo $resUser['dob']; ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <label for="address">Address</label>
                <textarea id="address" class="form-control" name="address" rows="3" placeholder="Enter address" disabled><?php echo $resUser['address']; ?></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="email">Email Address</label>
                <input id="email" class="form-control" type="email" name="email" placeholder="Enter email address" required disabled value="<?php echo $resUser['emailAddress']; ?>">
            </div>

            <div class="col-12 col-sm-6 ">
                <label for="permissions">Permissions</label>
                <input id="permissions" class="form-control" type="text" name="permissions" placeholder="" required disabled value="<?php echo $resUser['permission']; ?>">

            </div>
        </div>

        <div class="button-row d-flex justify-content-center mt-4">
            <button id="addUserBtn" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Submit">Add new User</button>
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