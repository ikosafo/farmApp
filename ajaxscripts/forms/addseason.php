<?php include('../../config.php'); ?>

<form autocomplete="off" id="farmProduceForm">
    <div class="row g-4">
         <div class="col-12 col-md-6">
            <label for="produce" class="form-label">Produce <span class="text-danger">*</span></label>
            <select id="produce" class="form-control border-radius-md" name="produce" required>
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
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveProduce" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Produce
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#saveProduce").click(function () {
            var $button = $(this);
            var $spinner = $button.find('.spinner-border');
            $spinner.removeClass('d-none');

            var startMonth = $("#startMonth").val();
            var endMonth = $("#endMonth").val();
            var duration = startMonth && endMonth ? startMonth + " - " + endMonth : "";

            var formData = {
                produceCategory: $("#produce").val(),
                seasonName: $("#seasonName").val(),
                duration: duration
            };

            var url = "ajaxscripts/queries/addSeason.php";

            var successCallback = function (response) {
                $spinner.addClass('d-none');
                if (response === 'Success') {
                    $.notify("Season saved successfully!", {
                        className: "success",
                        position: "top right"
                    });
                    $('#addproduceModal').modal('hide');
                    loadPage("ajaxscripts/tables/seasons.php", function (response) {
                        $('#seasonsTable').html(response);
                    });
                } else {
                    $.notify(response, {
                        className: "error",
                        position: "top right"
                    });
                }
            };

            var validateForm = function (formData) {
                var error = '';
                if (!formData.produceCategory) error += 'Please select produce\n';
                if (!formData.seasonName) error += 'Please enter season name\n';
                if (!formData.duration) error += 'Please select both start and end months\n';
                /* if (formData.duration) {
                    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    var startIndex = months.indexOf(startMonth);
                    var endIndex = months.indexOf(endMonth);
                    if (endIndex < startIndex) {
                        error += 'End month cannot be before start month\n';
                    }
                } */
                return error;
            };

            saveForm(formData, url, successCallback, validateForm);
        });
    });
</script>