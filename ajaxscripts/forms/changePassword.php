<?php include('../../config.php'); ?>

<form autocomplete="off" id="passwordForm">
    <div class="row g-3">
        <div class="col-12">
            <label for="currentPassword" class="form-label">Current Password <span class="text-danger">*</span></label>
            <input id="currentPassword" class="form-control border-radius-md" type="password" name="currentPassword" placeholder="Enter Current Password" required>
        </div>
        <div class="col-12">
            <label for="newPassword" class="form-label">New Password <span class="text-danger">*</span></label>
            <input id="newPassword" class="form-control border-radius-md" type="password" name="newPassword" placeholder="Enter New Password" required>
        </div>
        <div class="col-12">
            <label for="confirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <input id="confirmPassword" class="form-control border-radius-md" type="password" name="confirmPassword" placeholder="Confirm New Password" required>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <button type="button" id="updatePasswordBtn" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update Password
        </button>
    </div>
</form>


<script>
    $("#updatePasswordBtn").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var currentPassword = $("#currentPassword").val().trim();
        var newPassword = $("#newPassword").val().trim();
        var confirmPassword = $("#confirmPassword").val().trim();

        if (newPassword && newPassword.length < 6) {
            $.notify("Password should not be less than 6 characters", { className: "error", position: "top center" });
            $spinner.addClass('d-none');
            return;
        }

        if (!currentPassword || !newPassword || !confirmPassword) {
            $.notify("All fields are required.", { className: "error", position: "top center" });
            $spinner.addClass('d-none');
            return;
        }

        if (newPassword !== confirmPassword) {
            $.notify("New passwords do not match.", { className: "error", position: "top center" });
            $spinner.addClass('d-none');
            return;
        }

        $.ajax({
            type: "POST",
            url: "ajaxscripts/queries/updatePassword.php",
            data: {
                currentPassword: currentPassword,
                newPassword: newPassword
            },
            success: function (response) {
                $spinner.addClass('d-none');
                if (response === 'Success') {
                    $.notify("Password updated successfully.", { className: "success", position: "top center" });
                    $('#passwordForm')[0].reset();
                } else {
                    $.notify(response, { className: "error", position: "top center" });
                }
            }
        });
    });
</script>
