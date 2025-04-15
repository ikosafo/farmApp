function saveForm(formData, url, successCallback, validateForm) {
    var error = '';

    // Validate form fields using custom validation function
    if (validateForm && typeof validateForm === 'function') {
        error = validateForm(formData);
    }

    if (error === "") {
        // Perform AJAX request to save form data
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            beforeSend: function () {
                $.blockUI({ message: '<h1>Please wait...</h1>' });
            },
            success: function (response) {
                // Call the successCallback function with the response
                successCallback(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " " + thrownError);
            },
            complete: function () {
                $.unblockUI();
            }
        });
    } else {
        // Display error message
        $.notify(error, {
            position: "top center"
        });
    }

    return false;
}


function loadPage(url, successCallback) {
    $.ajax({
        url: url,
        beforeSend: function () {
            $.blockUI({ message: '<h3>Please wait...</h3>' });
        },
        success: function (response) {
            // Call the successCallback function with the response
            successCallback(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + " " + thrownError);
        },
        complete: function () {
            $.unblockUI();
        }
    });
}


function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isAmount(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    // Check if the character is a number (0-9) or a dot (.)
    if (charCode == 46 || (charCode >= 48 && charCode <= 57)) {
        // Check if the input already contains a dot
        if (charCode == 46 && evt.target.value.indexOf('.') !== -1) {
            return false;
        }
        return true;
    }

    return false;
}
