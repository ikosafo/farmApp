<?php include('../../config.php'); ?>

<form autocomplete="off" id="farmSeasonForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="produceCategory" class="form-label">Produce <span class="text-danger">*</span></label>
            <select id="produceCategory" class="form-control border-radius-md" name="produce" required>
                <option value="" disabled selected>Select a produce</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `producelist` WHERE `prodStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    echo "<option value='{$resCat['prodId']}'>{$resCat['prodName']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label for="seasonName" class="form-label">Season Name <span class="text-danger">*</span></label>
            <input id="seasonName" class="form-control border-radius-md" type="text" placeholder="Enter season name" required>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="startMonth" class="form-label">Start Month <span class="text-danger">*</span></label>
            <select id="startMonth" class="form-control border-radius-md" name="startMonth" required>
                <option value="" disabled selected>Select start month</option>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label for="endMonth" class="form-label">End Month <span class="text-danger">*</span></label>
            <select id="endMonth" class="form-control border-radius-md" name="endMonth" required>
                <option value="" disabled selected>Select end month</option>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveSeason" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Season
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#saveSeason").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        // Get form data
        var formData = {
            produceid: $("#produceCategory").val(),
            seasonName: $("#seasonName").val(),
            startMonth: $("#startMonth").val(),
            endMonth: $("#endMonth").val()
        };

        // Client-side validation
        var validateForm = function (formData) {
            var error = '';
            if (!formData.produceid) error += 'Please select a produce.\n';
            if (!formData.seasonName) error += 'Please enter a season name.\n';
            if (!formData.startMonth) error += 'Please select a start month.\n';
            if (!formData.endMonth) error += 'Please select an end month.\n';
            if (formData.startMonth && formData.endMonth && formData.startMonth === formData.endMonth) {
                error += 'Start month and end month cannot be the same.\n';
            }
            return error;
        };

        // Validate form
        var validationErrors = validateForm(formData);
        if (validationErrors) {
            $spinner.addClass('d-none');
            $.notify(validationErrors.trim(), {
                className: "error",
                position: "top center"
            });
            return;
        }

        // AJAX call to save data
        var url = "ajaxscripts/queries/addSeason.php";
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            success: function (response) {
                $spinner.addClass('d-none');
                if (response === 'Success') {
                    $.notify("Season saved successfully!", {
                        className: "success",
                        position: "top center"
                    });
                    $('#addseasonModal').modal('hide');
                    loadPage("ajaxscripts/tables/seasons.php", function (response) {
                        $('#seasonsTable').html(response);
                    });
                } else {
                    $.notify(response, {
                        className: "error",
                        position: "top center"
                    });
                }
            },
            error: function () {
                $spinner.addClass('d-none');
                $.notify("An error occurred while saving the season.", {
                    className: "error",
                    position: "top center"
                });
            }
        });
    });
});
</script>