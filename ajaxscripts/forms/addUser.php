<?php include('../../config.php'); ?>


<form autocomplete="off" id="addUserForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input id="fullName" class="form-control border-radius-md" type="text" name="fullName" placeholder="Enter full name" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="phoneNumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input id="phoneNumber" maxlength="10" class="form-control border-radius-md" type="tel" onkeypress="return isNumber(event)" name="phoneNumber" placeholder="Enter phone number" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="dateOfBirth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input id="dateOfBirth" class="form-control border-radius-md" type="text" name="dateOfBirth" placeholder="Select date" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="address" class="form-label">Home Address</label>
            <input id="address" class="form-control border-radius-md" type="text" name="address" placeholder="Enter address">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control border-radius-md" type="email" name="email" placeholder="Enter email address">
        </div>
        <div class="col-12 col-md-6">
            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
            <input id="username" class="form-control border-radius-md" type="text" name="username" placeholder="Enter username" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <input id="password" class="form-control border-radius-md" type="password" name="password" placeholder="Enter password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fa fa-eye-slash"></i>
                </button>
                <button class="btn btn-outline-info" type="button" id="generatePassword">Generate</button>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Permissions <span class="text-danger">*</span></label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="Financial Management" id="perm1">
                <label class="form-check-label" for="perm1">Financial Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="Equipment Management" id="perm2">
                <label class="form-check-label" for="perm2">Equipment Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="Field Management" id="perm3">
                <label class="form-check-label" for="perm3">Field Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="User Management" id="perm4">
                <label class="form-check-label" for="perm4">User Management</label>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
            <select id="role" class="form-control border-radius-md" name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveUser" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save User
        </button>
    </div>
</form>

<script>
    // flatpickr init: Max DOB is today - 10 years
    flatpickr("#dateOfBirth", {
        dateFormat: "Y-m-d",
        maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 10))
    });

    // Toggle password visibility
    $("#togglePassword").click(function() {
        const passField = $("#password");
        const icon = $(this).find("i");
        if (passField.attr("type") === "password") {
            passField.attr("type", "text");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
            passField.attr("type", "password");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        }
    });

    // Generate random password
    $("#generatePassword").click(function() {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        let password = "";
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        $("#password").val(password);
    });

    // Form submit
    $("#saveUser").click(function() {
        let permissions = [];
        $("input[name='permissions[]']:checked").each(function() {
            permissions.push($(this).val());
        });

        var formData = {
            fullName: $("#fullName").val(),
            email: $("#email").val(),
            username: $("#username").val(),
            password: $("#password").val(),
            role: $("#role").val(),
            dateOfBirth: $("#dateOfBirth").val(),
            address: $("#address").val(),
            phoneNumber: $("#phoneNumber").val(),
            permissions: permissions
        };

        var url = "ajaxscripts/queries/addUser.php";

        var successCallback = function(response) {
            if (response === 'Success') {
                $.notify("User added successfully", { className: "success", position: "top right" });
                $("#addUserForm")[0].reset();
                $('#addUserModal').modal('hide');
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
                $("#fullName").focus();
            }
            if (!formData.phoneNumber) {
                error += 'Please enter phone number\n';
                $("#phoneNumber").focus();
            }
            if (!formData.username) {
                error += 'Please enter username\n';
                $("#username").focus();
            }
            if (!formData.password) {
                error += 'Please enter password\n';
                $("#password").focus();
            }
            if (!formData.role) {
                error += 'Please select role\n';
                $("#role").focus();
            }
            if (!formData.dateOfBirth) {
                error += 'Please enter date of birth\n';
                $("#dateOfBirth").focus();
            }
            if (formData.permissions.length === 0) {
                error += 'Please select at least one permission\n';
            }
            if (formData.email && !validateEmail(formData.email)) {
                error += 'Please enter a valid email address\n';
                $("#email").focus();
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
