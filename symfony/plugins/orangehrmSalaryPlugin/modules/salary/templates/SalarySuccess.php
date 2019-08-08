<?php

use_stylesheet("http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css");
use_stylesheet("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css");
use_javascript("http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js");
use_javascript("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js");



define( "DB_HOST", "localhost" );
define( "DB_NAME", "career.loggcity" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "thir3a6-i" );

function connect()
{

    $connection = mysqli_connect( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );
    if (!$connection) {
        die( 'Could not connect database' );
    }
    return $connection;
}
$connection = connect();

$employee_name = 0;
$allowance = array();
$deduction = array();

function add_column_if_not_exist($table, $column, $column_attr)
{
    $connection = connect();
    $exists = false;
    $columns = mysqli_query($connection, "show columns from $table");
    while ($c = mysqli_fetch_assoc($columns)) {
        if ($c['Field'] == $column) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        mysqli_query($connection, "ALTER TABLE `$table` ADD `$column`  $column_attr");
    }
}

if (isset( $_POST["btnSave"] ) && $_POST["btnSave"] == "Generate") {

    if (isset($_POST["salary_status"]) && ((int)$_POST["salary_status"] == 1 || (int)$_POST["salary_status"] == 2))
        $salary_status = trim($_POST["salary_status"]);

    if (isset($_POST["employee_name"]))
        $employee_name = $_POST['employee_name'];



    foreach ($employee_name as $emp) {
        $query = "SELECT emp_allowance,emp_deduction,emp_other_name,emp_salary,emp_other_amount FROM hs_hr_employee WHERE emp_number = '" . $emp . "'";
        $result = mysqli_query($connection, $query) or die('could not select: ' . mysqli_error($connection));
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $allowance = explode(',', $row['emp_allowance']);
            ${'deduction'.$emp} = explode(',', $row['emp_deduction']);
            $otherAllowance = explode(',', $row['emp_other_name']);
            $otherAllowance_value = explode(',', $row['emp_other_amount']);
            $emp_salary = $row['emp_salary'];
        }

        $query = "SELECT employee_id FROM hs_hr_emp_salarybreak_allowance WHERE employee_id = '" . $emp . "'";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION["msg"] = '<div style="background-color: #dc3545 !important; color: #ffffff" class="message bg-danger fadable"> Salary Already Generated Against This User.<a href="#" class="messageCloseButton" style="z-index: 1;"></a></div>';

        } else {

            $id = 0;
            $values = "";
            $deduct_id = 0;
            for ($i = 0; $i < sizeof($allowance); $i++) {

                $sql = "SELECT allowance,percentage FROM hs_hr_emp_allowances WHERE id <> 0 AND status =1 AND id = " . $allowance[$i] . " ";
                $result = mysqli_query($connection, $sql);

                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                ${'calculated_values' . $emp}[] = array($row['allowance'] => ($emp_salary / 100) * $row['percentage']);
                if (array_key_exists('Basic', ${'calculated_values' . $emp}[$i])) {
                    $basic_value = ${'calculated_values' . $emp}[$i]['Basic'];
                }
            }
            for ($i = 0; $i < sizeof(${'deduction'.$emp}); $i++) {
                $sql = "SELECT deduction_name,rate,percentage,link_with_slab,link_with_basic FROM hs_hr_emp_deductions WHERE id <> 0 AND status =1 AND id = " . ${'deduction'.$emp}[$i] . " ";
                $result = mysqli_query($connection, $sql);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if ($row['percentage'] != 0 && $row["link_with_basic"] == 1) {
                    ${'calculated_deductions' . $emp}[] = array($row['deduction_name'] => (((float)$basic_value / 100) * $row['percentage']));
                }
                if ($row['rate'] != 0) {
                    ${'calculated_deductions' . $emp}[] = array($row['deduction_name'] => $row['rate']);
                }
            }
            $columns = mysqli_query($connection, "show columns from hs_hr_emp_salarybreak_allowance");
            while ($c = mysqli_fetch_assoc($columns)) {
                $feild[] = $c['Field'];
            }

            $columns = mysqli_query($connection, "show columns from hs_hr_emp_salarybreak_deduction");
            while ($c = mysqli_fetch_assoc($columns)) {
                $deduction_feild[] = $c['Field'];
            }
            $k = 0;
            $M = 0;
            for ($i = 0; $i < sizeof(${'calculated_values' . $emp}); $i++) {
                $exists = false;
                $keys = (array_keys(${'calculated_values' . $emp}[$i]));
                $column = strtolower(str_replace(' ', '_', $keys[0]));

                for ($j = 0; $j < sizeof($feild); $j++) {
                    if ($feild[$j] == $column) {
                        $exists = true;
                        $keys_reverse = ucfirst(str_replace('_', ' ', $keys[0]));
                    }
                }
                for ($j = 0; $j < sizeof(${'calculated_deductions' . $emp}); $j++) {
                    $deduction_exists = false;
                    $deduction_keys = (array_keys(${'calculated_deductions' . $emp}[$j]));
                    $deduction_column = strtolower(str_replace(' ', '_', $deduction_keys[0]));
                    for ($l = 0; $l < sizeof($deduction_feild); $l++) {

                        if ($deduction_feild[$l] == $deduction_column) {
                            $deduction_exists = true;
                            $deduction_keys_reverse = ucfirst(str_replace('_', ' ', $deduction_keys[0]));
                        } else {
                            add_column_if_not_exist('hs_hr_emp_salarybreak_deduction', $deduction_column, 'VARCHAR(32) NULL');
                            if ($deduction_feild[$l] == $deduction_column) {
                                $deduction_exists = true;
                                $deduction_keys_reverse = ucfirst(str_replace('_', ' ', $deduction_keys[0]));
                            }
                        }
                    }
                    if ($deduction_exists) {
                        if ($M == 0) {
                            $query = "INSERT INTO hs_hr_emp_salarybreak_deduction SET  " . $deduction_column . " = " . ${'calculated_deductions' . $emp}[$j][$deduction_keys_reverse] . " ";
                            $result = mysqli_query($connection, $query) or die ('Could not insert other allowance because: ' . mysqli_error($connection) . '\n' . $query);
                            $deduct_id = mysqli_insert_id($connection);

                        } else {
                            $query = "UPDATE hs_hr_emp_salarybreak_deduction SET  " . $deduction_column . " = " . ${'calculated_deductions' . $emp}[$j][$deduction_keys_reverse] . " WHERE id = " . $deduct_id . " ";
                            $result = mysqli_query($connection, $query) or die ('Could not insert other allowance because: ' . mysqli_error($connection) . '\n' . $query);
                        }
                    }
                    $M++;
                }

                if ($exists) {
                    $allowance_ids = implode(',', $allowance);
                    $deduction_ids = implode(',', $deduction);
                    if ($k == 0) {

                        $query3 = "INSERT INTO hs_hr_emp_salarybreak_allowance SET " . $column . " = '" . ${'calculated_values' . $emp}[$i][$keys_reverse] . "', allowance_id='" . $allowance_ids . "',deduction_id ='" . $deduct_id . "', employee_id ='" . $emp . "' , status='" . (int)$salary_status . "', perform_by = '" . $sf_user->getAttribute('auth.empNumber') . "', date_added=NOW()";
                        $result = mysqli_query($connection, $query3) or die ('Could not insert allowance because: ' . mysqli_error($connection) . '\n' . $query3);
                        $k++;
                        $id3 = mysqli_insert_id($connection);

                    } else {

                        $query = "UPDATE hs_hr_emp_salarybreak_allowance SET " . $column . " = '" . ${'calculated_values' . $emp}[$i][$keys_reverse] . "' WHERE id = " . $id3 . " ";
                        $result = mysqli_query($connection, $query) or die ('Could not insert allowance because: ' . mysqli_error($connection) . "\n" . $query);

                        $_SESSION["msg"] = '<div class="message success fadable"> Successfully Generated<a href="#" class="messageCloseButton"></a></div>';

                        header("Location:  " . $_SERVER['PHP_SELF'] . "", true);
                    }
                }
            }
        }

    }
}

?>
<?php if(!empty($statusMsg)){ ?>
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
<?php } ?>

<div id="salary" class="box">
    <div class="head"><h1 id="taxHeading"><?php echo "Generate Salary"; ?></h1></div>

    <div class="inner">


        <form name="frmTax" id="frmTax" class="form-horizontal" method="post" onsubmit="Validation()">

           <div class="form-group" style="display: none;">
               <label class="control-label col-sm-2">ID</label>
               <div class="col-sm-3">
                   <input type="text" name="id" class="form-control" maxlength="100" id="salary_id">
               </div>
           </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Employee *</label>
                <div class="col-sm-3">
                    <select name="employee_name[]" id="employee_name" class="form-control example-selectAllNumber" multiple="multiple">
                        <?php
                        $query = "SELECT emp_number,emp_firstname,emp_lastname FROM hs_hr_employee WHERE emp_number <> 0";
                        $result = mysqli_query($connection,$query);
                        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

                            ?>
                            <option value='<?php echo $row["emp_number"]; ?>'> <?php echo $row['emp_firstname']. ' '. $row['emp_lastname'];?></option>
                        <?php  } ?>

                    </select>
                    <span class="error error_emp" style="margin-left: 1%"></span>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Status</label>

                <div class="col-sm-2">
                    <label class="custom-control-label" for="customRadioInline1">Generated</label>
                    <input type="radio" id="enable" value="1" checked="checked" name="salary_status" class="custom-control-input">

                    <label class="custom-control-label" for="customRadioInline2">Paid</label>
                    <input type="radio" id="disable" value="2" name="salary_status" class="custom-control-input">
                </div>
            </div>

                <p>
                    <input type="submit" class="savebutton"  name="btnSave" id="btnSave" value="<?php echo "Generate"; ?>"/>

                    <input type="button" class="reset" name="btnCancel" id="btnCancel" value="<?php echo "Cancel";?>"/>
                </p>
            </fieldset>
        </form>
    </div>
</div>

<div id="salary_report" class="box">

    <div class="head"><h1 id="taxHeading"><?php echo"Salary Report"; ?></h1></div>

    <div class="inner">
        <div class="top">

            <form id="printForm" action="../../../../services/salary/printing/payslip.php" method="post"
                  target="_blank">
                <input type="hidden" name="employee_id" id="employee_id" value="">
            <input type="button" class="reset" id="btnCancelReport" name="btnCancelReport" value="Close">
            </form>
        </div>
        <div id="Report"></div>

    </div>
</div>
<div id="salarylist" class="box">


    <div class="head"><h1 id="taxHeading"><?php echo "Salary Allowances"; ?></h1></div>

    <div class="inner">
        <div class="top">


            <input type="button" class="" id="btnAdd" name="btnAdd" value="Add">
            <input type="submit" class="delete" id="btnDelete" name="btnDelete" value="Delete" data-toggle="modal" data-target="#deleteConfModal" disabled="disabled">
            <input type="button" class="" id="btnPrintAll" name="btnPrintAll" value="Print Selected">

        </div>
        <div id="helpText" class="helpText"> <?php

            if(isset($_SESSION["msg"]))
            {
                echo $_SESSION["msg"];
                $_SESSION["msg"]="";
            }
            ?>								</div>

        <div id="scrollWrapper">
            <div id="scrollContainer">
            </div>
        </div>
        <div id="tableWrapper">
            <table class="table hover" id="resultTable">

                <thead>
                <tr><th rowspan="1" class="checkbox-col">
                        <input type="checkbox" id="ohrmList_chkSelectAll" name="chkSelectAll" value="" />
                    </th>
                    <th rowspan="1" style="" >Employee Name</th>
                    <th rowspan="1" style="" >Basic</th>
                    <th rowspan="1" style="" >House Rent</th>
                    <th rowspan="1" style="" >Utilities</th>
                    <th rowspan="1" style="" >Medical</th>
                    <th rowspan="1" style="" >Transport</th>
                    <th rowspan="1" style="" >Status</th>
                    <th rowspan="1" style="" >Date Added</th>
                    <th rowspan="1" style="" >Perform By</th>
                    <th rowspan="1" style="" >Action</th>

                </tr>
                </thead>

                <tbody>

                <?php
                $connection = connect();

                $query = "SELECT s.id,s.basic,s.house_rent,s.utilities,s.medical,s.transport,s.status,s.date_added,r.emp_firstname AS firstname,r.emp_lastname AS lastname,s.employee_id,e.emp_firstname,e.emp_lastname FROM hs_hr_emp_salarybreak_allowance s LEFT JOIN hs_hr_employee e ON s.perform_by = e.emp_number LEFT JOIN hs_hr_employee r ON s.employee_id = r.emp_number WHERE s.id <> 0 ORDER BY s.basic";
                $result = mysqli_query($connection,$query) or die($connection);
                $num = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    ?>


                    <tr id="edit" class=" <?php echo (($num%2 == 0) ? 'even' : 'odd');?>">
                        <td><input type="checkbox" class="ohrmList_chkSelect" id="ohrmList_chkSelectRecord_2"  name="chkSelectRow[]"  data-salary-id="<?php echo $row["id"]; ?>" /></td>
                        <td class="left" style="display: none;" ><?php echo $row['id'];?></td>
                        <td ><?php echo $row['firstname'].' '. $row['lastname'];?></td>
                        <td><?php echo $row['basic'];?></td>
                        <td><?php echo $row['house_rent'];?></td>
                        <td><?php echo $row['utilities'];?></td>
                        <td><?php echo $row['medical'];?></td>
                        <td><?php echo $row['transport'];?></td>
                        <td><?php echo (($row['status']) == '1' ? 'Generated' : 'Paid');?></td>
                        <td><?php echo $row['date_added'];?></td>
                        <td><?php echo $row['emp_firstname'].' '.$row['emp_lastname'];?></td>
                        <td> <input type="button" id="btnView" name="btnView" value="View"></td>

                    </tr>

                    <?php $num--; } ?>
                </tbody>
            </table>
        </div> <!-- tableWrapper -->

    </div>
</div>



<!-- Confirmation box HTML: Ends -->
<script type="text/javascript">

    $(document).ready(function() {
        $('.example-selectAllNumber').multiselect({
            includeSelectAllOption: true,
            selectAllNumber: false
        });
    });


    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    $('#btnPrint').on('click',function (e) {
       document.getElementById('printForm').submit();
    });

    $(document).on('click', '#ohrmList_chkSelectAll', function() {
        $(".ohrmList_chkSelect").prop("checked", this.checked);
        $("#select_count").html($("input.ohrmList_chkSelect:checked").length+" Selected");
    });
    $(document).on('click', '.ohrmList_chkSelect', function() {

        $("#select_count").html($("input.ohrmList_chkSelect:checked").length+" Selected");
    });

// Print Selected Record
    $('#btnPrintAll').on('click', function(e) {
        var employee = [];
        $(`.ohrmList_chkSelect:checked`).each(function () {
            employee.push($(this).data('salary-id'));
        });
        if (employee.length <= 0) {
            alert("Please select records.");
        } else {

            document.getElementById('employee_id').value = employee;
            $('#printForm').submit();
        }
    });
    // delete selected records
    $('#btnDelete').on('click', function(e) {
        var employee = [];
        $(`.ohrmList_chkSelect:checked`).each(function() {
            employee.push($(this).data('salary-id'));
        });
        if(employee.length <=0) {
            alert("Please select records.");
        }
        else
        {
            WRN_PROFILE_DELETE = "Are you sure you want to delete "+(employee.length>1?"these":"this")+" row?";
            var checked = confirm(WRN_PROFILE_DELETE);
            if(checked == true) {
                var selected_values = employee.join(",");
                $.ajax({
                    type: "POST",
                    url: "../../../../services/salary/delete_salary.php",
                    cache:false,
                    data: 'salary_id='+selected_values,
                    success: function(response) {

// remove deleted employee rows
                            var emp_ids = response.split(",");
                            for (var i = 0; i < emp_ids.length; i++) {
                                $("#" + emp_ids[i]).remove();
                            }
                            location.reload();


                    }
                });
            }
        }
    });



    $(document).ready(function() {

        $('#btnSave').click(function() {
            $('#frmTax').submit();
        });

        $('#salary').hide();
        $('#salary_report').hide();
        $(".otherSection").hide();
        $('#btnAdd').click(function() {
            $('#salary').show();
            $('.top').hide();
            $('.error').hide();
            $('#allowance').val('');
            $('#deduction').val('');
            $('#employee_name').val('');
            $('#basic').val('');
            $('#personal_optGender_1').val('');
            $('#personal_optGender_2').val('');
            $(".messageBalloon_success").remove();
        });

        $('#btnCancel').click(function() {
            $('#salary').hide();
            $('.top').show();
            $('#btnDelete').show();
            $('.error').hide();

        });

        $('#btnCancelReport').click(function() {
            $('#salary_report').hide();

        });

        $('#btnDelete').attr('disabled', 'disabled');

        $(':checkbox[name*="chkSelectRow[]"]').click(function() {
            if($(':checkbox[name*="chkSelectRow[]"]').is(':checked')) {
                $('#btnDelete').removeAttr('disabled');
            } else {
                $('#btnDelete').attr('disabled','disabled');
            }
        });


    });

    $(document).ready(function(){
        $('#ohrmList_chkSelectAll').on('click',function(){
            if(this.checked){
                $('.ohrmList_chkSelect').each(function(){
                    this.checked = true;
                });
                $('#btnDelete').removeAttr('disabled');

            }else{
                $('.ohrmList_chkSelect').each(function(){
                    this.checked = false;
                });
                $('#btnDelete').attr('disabled','disabled');


            }
        });


    });
    $("#chkOther").click(function() {
        $(".otherSection").hide();

        $("#otherAllowance").val("");
        $("#otherAllowance_value").val("");

        if($("#chkOther").is(':checked')) {
            $(".otherSection").show();
        }
    });
    $(document).ready(function(){

            $('#resultTable tbody #btnView').on('click', function(e) {
                var id = $(this).closest('tr').find('.left').text();
                getSalaryData(id);

            });


        });

    function Validation() {
        var allowance = document.forms["frmTax"]["allowance"];
        var basic = document.forms["frmTax"]["basic"];
        var deduction = document.forms["frmTax"]["deduction"];
        var employee = document.forms["frmTax"]["employee_name"];
        var status = document.forms["frmTax"]["salary_status"];
        var otherAllowance = document.forms["frmTax"]["otherAllowance"];
        var otherAllowance_value = document.forms["frmTax"]["otherAllowance_value"];
        if (employee.value == "") {
            $('.error_emp').html("Please select employee.");
            $('.error_emp').show();
            employee.focus();
            return false;
        }else{
            $('.error_emp').hide();
        }
        if (basic.value == "") {
            $('.error_pack').html("Please enter basic.");
            $('.error_pack').show();
            basic.focus();
            return false;
        }else{
            $('.error_pack').hide();
        }
        if (allowance.value == "") {
            $('.error_allow').html("Please select allowance name.");
            $('.error_allow').show();
            allowance.focus();
            return false;
        }else{
            $('.error_allow').hide();
        }

        if (deduction.value == "") {
            $('.error_deduct').html("Please select deduction.");
            $('.error_deduct').show();
            deduction.focus();
            return false;
        }else{
            $('.error_deduct').hide();
        }

        if (status.value == "") {
            $('.error_status').html("Please select status.");
            $('.error_status').show();
            status.focus();
            return false;
        }else{
            $('.error_status').hide();
        }
        if($("#chkOther").is(':checked')) {
            if (otherAllowance.value == "") {
                $('.error_allowance').html("Please enter allowance name.");
                $('.error_allowance').show();
                otherAllowance.focus();
                return false;
            } else {
                $('.error_allowance').hide();
            }
            if (otherAllowance_value.value == "") {
                $('.error_allowance_value').html("Please enter allowance value.");
                $('.error_allowance_value').show();
                otherAllowance_value.focus();
                return false;
            } else {
                $('.error_allowance_value').hide();
            }
        }

        return true;
        document.getElementById("frmTax").reset();
    }
    function getSalaryData(id) {

        if (id == "") {
            document.getElementById("Report").innerHTML = "";
            $('#salary_report').hide();
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    document.getElementById("Report").innerHTML = this.responseText;
                    $('#salary_report').show();

                }else{
                    $('#salary_report').hide();
                }
            };
            xmlhttp.open("GET","../../../../services/salary/get_salary.php?id="+id,true);
            xmlhttp.send();
            document.getElementById('employee_id').value = id;

        }
    }


</script>


