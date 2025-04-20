<?php include('../../config.php'); ?>
<form autocomplete="off" id="farmExpenditureForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn" style="margin-bottom: 30px;">
        <h5 class="font-weight-bolder mb-0">Report and Printing</h5>

        <div class="row mt-3">
            <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                <label for="reportCategory">Category</label>
                <select id="reportCategory" class="form-select" required>
                    <option value="">Select Category</option>
                    <option>Expenditure</option>
                    <option>Income</option>
                    <option>Trial Balance</option>
                    <option>Orders</option>
                </select>
            </div>

            <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                <label for="reportStartDate">Date From</label>
                <input id="reportStartDate" class="form-control" type="date" placeholder="Enter date" required>
            </div>

            <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                <label for="reportEndDate">Date To</label>
                <input id="reportEndDate" class="form-control" type="date" placeholder="Enter date" required>
            </div>

            <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                <button style="width: 80% !important; float: left; margin-top: 30px;" id="searchReportBtn" class="btn btn-sm bg-gradient-dark mb-0 js-btn-next" type="button" title="Search Report">Search RECORD <i data-feather="chevrons-right"></i>
                </button>
            </div>

        </div>

    </div>
</form>

<script>
    feather.replace();

    $("#reportStartDate").flatpickr();
    $("#reportEndDate").flatpickr();

    $("#searchReportBtn").click(function() {
        var formData = {
            reportCategory: $("#reportCategory").val(),
            reportStartDate: $("#reportStartDate").val(),
            reportEndDate: $("#reportEndDate").val(),
        };

        var url = "ajaxscripts/queries/searchReport.php";

        var successCallback = function(response) {
            //alert(response);
            console.log(response);
            $('#pageTable').html(response);
        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.reportCategory) {
                error += 'Please select category\n';
                $("#reportCategory").focus();
            }
            if (!formData.reportStartDate) {
                error += 'Please select start date \n';
                $("#reportStartDate").focus();
            }
            if (!formData.reportEndDate) {
                error += 'Please select end date \n';
                $("#reportEndDate").focus();
            }
            if (formData.reportEndDate && formData.reportEndDate < formData.reportStartDate) {
                error += 'Please select appropriate date range\n';
                $("#reportEndDate").focus();
            }

            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>