<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getExp = $mysqli->query("select * from expenditures where expId = '$i_id'");
$resExp = $getExp->fetch_assoc();

?>
<form autocomplete="off" id="farmExpenditureForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Farm Expenditure</h5>
        <p class="mb-0 text-sm">Edit farm expenditure</p>

        <div class="row mt-3">
            <div class="col-12">
                <label for="expenditureName">Expenditure Name</label>
                <input id="expenditureName" class="form-control" type="text" name="expenditureName" value="<?php echo $resExp['expenditureName']; ?>" placeholder="Enter expenditure name" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <label for="expenditureDescription">Expenditure Description</label>
                <textarea id="expenditureDescription" class="form-control" name="expenditureDescription" rows="3" placeholder="Enter expenditure description"><?php echo $resExp['expenditureDescription']; ?></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="expenditureCategory">Expenditure Category</label>
                <select id="expenditureCategory" class="form-select" name="expenditureCategory" required>
                    <option value="">Select Category</option>
                    <?php
                    $expCat = $resExp['expenditureCategory'];
                    $getCat = $mysqli->query("select * from expense_category where ecatActive = 1");
                    while ($resCat = $getCat->fetch_assoc()) { ?>
                        <option <?php if (@$expCat == $resCat['ecatName']) echo "Selected" ?>><?php echo $resCat['ecatName'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="expenditureAmount">Amount</label>
                <input id="expenditureAmount" class="form-control" type="text" onkeypress="return isAmount(event)" value="<?php echo $resExp['expenditureAmount']; ?>" name="expenditureAmount" min="0" step="0.01" placeholder="Enter amount" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="expenditureDate">Date</label>
                <input id="expenditureDate" class="form-control" type="date" name="expenditureDate" placeholder="Enter date" required value="<?php echo $resExp['expenditureDate']; ?>">
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="expenditureReceipt">Receipt Number</label>
                <input id="expenditureReceipt" class="form-control" type="text" name="expenditureReceipt" placeholder="Enter receipt number" value="<?php echo $resExp['expenditureReceipt']; ?>">
            </div>
        </div>

        <div class="button-row d-flex justify-content-center mt-4">
            <button id="editExpenditure" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Submit">Update</button>
        </div>
    </div>
</form>

<script>
    $("#editExpenditure").click(function() {
        var formData = {
            expenditureName: $("#expenditureName").val(),
            expenditureDescription: $("#expenditureDescription").val(),
            expenditureCategory: $("#expenditureCategory").val(),
            expenditureAmount: $("#expenditureAmount").val(),
            expenditureDate: $("#expenditureDate").val(),
            expenditureReceipt: $("#expenditureReceipt").val(),
            i_id: '<?php echo $i_id; ?>'
        };

        var url = "ajaxscripts/queries/editExpenditure.php";

        var successCallback = function(response) {
            //alert(response);
            console.log(response);
            if (response === 'Success') {
                $.notify("Form updated successfully", "success");
                loadPage("ajaxscripts/forms/addExpenditure.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/expenditure.php", function(response) {
                    $('#pageTable').html(response);
                });
            } else {
                alert(response);
            }
        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.expenditureName) {
                error += 'Please enter expenditure name\n';
                $("#expenditureName").focus();
            }
            if (!formData.expenditureCategory) {
                error += 'Please select category\n';
                $("#expenditureCategory").focus();
            }
            if (!formData.expenditureAmount) {
                error += 'Please enter amount\n';
                $("#expenditureAmount").focus();
            }
            if (!formData.expenditureDate) {
                error += 'Please select date\n';
                $("#expenditureDate").focus();
            }
            // You can add more validation rules as needed

            return error;
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback, validateForm);
    });
</script>