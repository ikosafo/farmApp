<?php
include('../../config.php');

// Fetch current currency values
$query = $mysqli->query("SELECT currencyghs, currencyusd, currencyeur FROM currencies LIMIT 1");
$row = $query->fetch_assoc();

$currencyghs = $row['currencyghs'] ?? '1.0';
$currencyusd = $row['currencyusd'] ?? '';
$currencyeur = $row['currencyeur'] ?? '';
?>

<form autocomplete="off" id="passwordForm">
    <div class="row g-3">
        <div class="col-12">
            <label for="currencyghs" class="form-label">GHS <span class="text-danger">*</span></label>
            <input id="currencyghs" class="form-control border-radius-md" type="text" name="currencyghs" value="<?= htmlspecialchars($currencyghs) ?>" placeholder="Enter Current for GHS" required>
        </div>
        <div class="col-12">
            <label for="currencyusd" class="form-label">USD <span class="text-danger">*</span></label>
            <input id="currencyusd" class="form-control border-radius-md" type="text" name="currencyusd" value="<?= htmlspecialchars($currencyusd) ?>" placeholder="Enter Current for USD" required>
        </div>
        <div class="col-12">
            <label for="currencyeur" class="form-label">EUR <span class="text-danger">*</span></label>
            <input id="currencyeur" class="form-control border-radius-md" type="text" name="currencyeur" value="<?= htmlspecialchars($currencyeur) ?>" placeholder="Enter Current for EUR" required>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <button type="button" id="updateCurrencyBtn" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update Currencies
        </button>
    </div>
</form>


<script>
    $("#updateCurrencyBtn").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var currencyghs = $("#currencyghs").val().trim();
        var currencyusd = $("#currencyusd").val().trim();
        var currencyeur = $("#currencyeur").val().trim();

        var decimalRegex = /^\d+(\.\d{1,4})?$/;

        if (!currencyghs) {
            $.notify("GHS field is required.", { className: "error", position: "top center" });
            $("#currencyghs").focus();
            $spinner.addClass('d-none');
            return;
        }
        if (!decimalRegex.test(currencyghs)) {
            $.notify("GHS must be a valid number.", { className: "error", position: "top center" });
            $("#currencyghs").focus();
            $spinner.addClass('d-none');
            return;
        }

        if (!currencyusd) {
            $.notify("USD field is required.", { className: "error", position: "top center" });
            $("#currencyusd").focus();
            $spinner.addClass('d-none');
            return;
        }
        if (!decimalRegex.test(currencyusd)) {
            $.notify("USD must be a valid number.", { className: "error", position: "top center" });
            $("#currencyusd").focus();
            $spinner.addClass('d-none');
            return;
        }

        if (!currencyeur) {
            $.notify("EUR field is required.", { className: "error", position: "top center" });
            $("#currencyeur").focus();
            $spinner.addClass('d-none');
            return;
        }
        if (!decimalRegex.test(currencyeur)) {
            $.notify("EUR must be a valid number.", { className: "error", position: "top center" });
            $("#currencyeur").focus();
            $spinner.addClass('d-none');
            return;
        }

        $.ajax({
            type: "POST",
            url: "ajaxscripts/queries/updateCurrencies.php",
            data: {
                currencyghs: currencyghs,
                currencyusd: currencyusd,
                currencyeur: currencyeur
            },
            success: function (response) {
                $spinner.addClass('d-none');
                if (response === 'Success') {
                    $.notify("Currencies updated successfully.", { className: "success", position: "top center" });
                } else {
                    $.notify(response, { className: "error", position: "top center" });
                }
            }
        });
    });

</script>
