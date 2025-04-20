<?php
include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getUser = $mysqli->query("select * from users where uId = '$i_id'");
$resUser = $getUser->fetch_assoc();

// Split permissions into an array for checkbox handling
$permissionsArray = explode(',', $resUser['permission']);
?>

<form autocomplete="off" id="addUserForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input id="fullName" class="form-control border-radius-md" type="text" name="fullName" value="<?php echo htmlspecialchars($resUser['fullName']); ?>" readonly>
        </div>
        <div class="col-12 col-md-6">
            <label for="phoneNumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input id="phoneNumber" class="form-control border-radius-md" type="tel" name="phoneNumber" value="<?php echo htmlspecialchars($resUser['phoneNumber']); ?>" readonly>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="dateOfBirth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input id="dateOfBirth" class="form-control border-radius-md" type="text" name="dateOfBirth" value="<?php echo htmlspecialchars($resUser['dob']); ?>" readonly>
        </div>
        <div class="col-12 col-md-6">
            <label for="address" class="form-label">Home Address</label>
            <input id="address" class="form-control border-radius-md" type="text" name="address" value="<?php echo htmlspecialchars($resUser['address']); ?>" readonly>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control border-radius-md" type="email" name="email" value="<?php echo htmlspecialchars($resUser['emailAddress']); ?>" readonly>
        </div>
        <div class="col-12 col-md-6">
            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
            <input id="username" class="form-control border-radius-md" type="text" name="username" value="<?php echo htmlspecialchars($resUser['username']); ?>" readonly>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
            <select id="role" class="form-control border-radius-md" name="role" disabled>
                <option value="admin" <?php echo $resUser['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $resUser['role'] == 'user' ? 'selected' : ''; ?>>User</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Permissions <span IRS Tax Formsclass="text-danger">*</span></label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="Financial Management" id="perm1" <?php echo in_array('Financial Management', $permissionsArray) ? 'checked' : ''; ?> disabled>
                <label class="form-check-label" for="perm1">Financial Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="Equipment Management" id="perm2" <?php echo in_array('Equipment Management', $permissionsArray) ? 'checked' : ''; ?> disabled>
                <label class="form-check-label" for="perm2">Equipment Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="Field Management" id="perm3" <?php echo in_array('Field Management', $permissionsArray) ? 'checked' : ''; ?> disabled>
                <label class="form-check-label" for="perm3">Field Management</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="User Management" id="perm4" <?php echo in_array('User Management', $permissionsArray) ? 'checked' : ''; ?> disabled>
                <label class="form-check-label" for="perm4">User Management</label>
            </div>
        </div>
    </div>


    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>