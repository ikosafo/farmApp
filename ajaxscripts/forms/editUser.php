<?php
include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getUser = $mysqli->query("select * from users where uId = '$i_id'");
$resUser = $getUser->fetch_assoc();

$permissionsArray = explode(',', $resUser['permission']);
?>

<form autocomplete="off" id="editUserFormEdit">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="fullNameEdit" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input id="fullNameEdit" class="form-control border-radius-md" type="text" name="fullNameEdit" value="<?php echo htmlspecialchars($resUser['fullName']); ?>" placeholder="Enter full name" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="phoneNumberEdit" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input id="phoneNumberEdit" maxlength="10" class="form-control border-radius-md" type="tel" onkeypress="return isNumber(event)" name="phoneNumberEdit" value="<?php echo htmlspecialchars($resUser['phoneNumber']); ?>" placeholder="Enter phone number" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="dateOfBirthEdit" class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input id="dateOfBirthEdit" class="form-control border-radius-md" type="text" name="dateOfBirthEdit" value="<?php echo htmlspecialchars($resUser['dob']); ?>" placeholder="Select date" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="addressEdit" class="form-label">Home Address</label>
            <input id="addressEdit" class="form-control border-radius-md" type="text" name="addressEdit" value="<?php echo htmlspecialchars($resUser['address']); ?>" placeholder="Enter address">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="emailEdit" class="form-label">Email</label>
            <input id="emailEdit" class="form-control border-radius-md" type="email" name="emailEdit" value="<?php echo htmlspecialchars($resUser['emailAddress']); ?>" placeholder="Enter email address">
        </div>
        <div class="col-12 col-md-6">
            <label for="usernameEdit" class="form-label">Username <span class="text-danger">*</span></label>
            <input id="usernameEdit" class="form-control border-radius-md" type="text" name="usernameEdit" value="<?php echo htmlspecialchars($resUser['username']); ?>" placeholder="Enter username" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="roleEdit" class="form-label">Role <span class="text-danger">*</span></label>
            <select id="roleEdit" class="form-control border-radius-md" name="roleEdit" required>
                <option value="" disabled>Select Role</option>
                <option value="admin" <?php echo $resUser['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $resUser['role'] == 'user' ? 'selected' : ''; ?>>User</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Permissions <span class="text-danger">*</span></label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissionsEdit[]" value="Financial Management" id="perm1Edit" <?php echo in_array('Financial Management', $permissionsArray) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="perm1Edit">Financial Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissionsEdit[]" value="Equipment Management" id="perm2Edit" <?php echo in_array('Equipment Management', $permissionsArray) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="perm2Edit">Equipment Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissionsEdit[]" value="Field Management" id="perm3Edit" <?php echo in_array('Field Management', $permissionsArray) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="perm3Edit">Field Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissionsEdit[]" value="User Management" id="perm4Edit" <?php echo in_array('User Management', $permissionsArray) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="perm4Edit">User Management</label>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="updateUserEdit" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update User
        </button>
    </div>
</form>

<script>
    // flatpickr init: Max DOB is today - 10 years
    flatpickr("#dateOfBirthEdit", {
        dateFormat: "Y-m-d",
        maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 10))
    });

    // Form submit
    $("#updateUserEdit").click(function() {
        let permissions = [];
        $("input[name='permissionsEdit[]']:checked").each(function() {
            permissions.push($(this).val());
        });

        var formData = {
            userId: '<?php echo $i_id; ?>',
            fullName: $("#fullNameEdit").val(),
            email: $("#emailEdit").val(),
            username: $("#usernameEdit").val(),
            role: $("#roleEdit").val(),
            dateOfBirth: $("#dateOfBirthEdit").val(),
            address: $("#addressEdit").val(),
            phoneNumber: $("#phoneNumberEdit").val(),
            permissions: permissions
        };

        var url = "ajaxscripts/queries/editUser.php";

        var successCallback = function(response) {
            if (response === 'Success') {
                $.notify("User updated successfully", { className: "success", position: "top right" });
                $("#editUserFormEdit")[0].reset();
                $('#editUserModal').modal('hide');
                loadPage("ajaxscripts/tables/users.php", function(response) {
                    $('#userTable').html(response);
                });
            } else {
                $.notify(response, { className: "error", position: "top right" });
            }
        };

        var validateForm = function(formData) {
            let error = '';
            if (!formData.fullName) {
                error += 'Please enter full name\n';
                $("#fullNameEdit").focus();
            }
            if (!formData.phoneNumber) {
                error += 'Please enter phone number\n';
                $("#phoneNumberEdit").focus();
            }
            if (!formData.username) {
                error += 'Please enter username\n';
                $("#usernameEdit").focus();
            }
            if (!formData.role) {
                error += 'Please select role\n';
                $("#roleEdit").focus();
            }
            if (!formData.dateOfBirth) {
                error += 'Please enter date of birth\n';
                $("#dateOfBirthEdit").focus();
            }
            if (formData.permissions.length === 0) {
                error += 'Please select at least one permission\n';
            }
            if (formData.email && !validateEmail(formData.email)) {
                error += 'Please enter a valid email address\n';
                $("#emailEdit").focus();
            }
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });

    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    function isNumber(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }
</script>