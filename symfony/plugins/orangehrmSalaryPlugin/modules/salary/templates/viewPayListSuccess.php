<?php

use_stylesheet("http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css");
use_stylesheet("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css");
use_javascript("http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js");
use_javascript("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js");
;
define( "DB_HOST", "localhost" );
define( "DB_NAME", "orangehrm" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );

function connect()
{

    $connection = mysqli_connect( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );
    if (!$connection) {
        die( 'Could not connect database' );
    }
    return $connection;
}
$connection = connect();
function CurrencyFormat($number)
{
    $decimalplaces = 2;
    $decimalcharacter = '.';
    $thousandseparater = ',';
    return number_format( $number, $decimalplaces, $decimalcharacter, $thousandseparater );
}

if (isset( $_POST["btnSave"] ) && $_POST["btnSave"] == "Generate") {


    if (isset($_POST["paylist_status"]) && ((int)$_POST["paylist_status"] == 1 || (int)$_POST["paylist_status"] == 2))
        $paylist_status = trim($_POST["paylist_status"]);

    if (isset($_POST["paylist_date"]))
        $paylist_date = $_POST['paylist_date'];

    $payroll_month = date('m', strtotime($_POST['paylist_date']));


    $query2 = "SELECT id FROM ohrm_paylist WHERE EXTRACT(MONTH FROM date_added) = '" . $payroll_month . "' ";
    $result2 = mysqli_query($connection, $query2) or die('could not get: ' . mysqli_error($connection));
    if (mysqli_num_rows($result2) > 0) {
        $_SESSION["msg"] = '<div class="alert alert-danger">Pay List already generated for this month.</div>';

    } else {

        $query = "SELECT id FROM hs_hr_payroll WHERE EXTRACT(MONTH FROM date_added) = '" . $payroll_month . "' ";
        $result = mysqli_query($connection, $query) or die('could not get: ' . mysqli_error($connection));
        if (mysqli_num_rows($result) > 0) {

//get invoices data
            $query = "SELECT emp_number FROM hs_hr_employee WHERE account_number <> '' ";
            $result = mysqli_query($connection, $query) or die('could not get: ' . mysqli_error($connection));
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $salary_id[] = $row['emp_number'];
            }

            $employee = implode(',', $salary_id);
            $query = "INSERT INTO ohrm_paylist SET salary_id = '" . $employee . "', status = '" . $paylist_status . "',date_added = '" . $paylist_date . "',perform_by = '" . $sf_user->getAttribute('auth.empNumber') . "'";
            $result = mysqli_query($connection, $query) or die('could not get: ' . mysqli_error($connection));
            if ($result) {
                $_SESSION["msg"] = '<div class="alert alert-success alert-dismissible">Pay List Generated.</div>';
            }

        } else {
            $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissible  ">Payroll not generated for this month.</div>';
        }
    }
}
$_SESSION['start'] = time();

// taking now logged in time
$_SESSION['expire'] = $_SESSION['start'] + (1) ;
$now = time();
if($now > $_SESSION['expire'])

{
    session_destroy();
}

?>
<?php if(!empty($statusMsg)){ ?>
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
<?php } ?>

<div id="payroll" class="box">
    <div class="head"><h1 id="taxHeading"><?php echo "Generate Pay List"; ?></h1></div>

    <div class="inner">


        <form name="frmTax" id="frmTax" class="form-horizontal" method="post" onsubmit="Validation()">

           <div class="form-group" style="display: none;">
               <label class="control-label col-sm-2">ID</label>
               <div class="col-sm-3">
                   <input type="text" name="id" class="form-control" maxlength="100" id="payroll_id">
               </div>
           </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Date*</label>
                <div class="col-sm-3">
                   <input type="date" class="form-control" name="paylist_date">
                    <span class="error error_emp" style="margin-left: 1%"></span>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Status</label>

                <div class="col-sm-2">
                    <label class="custom-control-label" for="customRadioInline1">Generated</label>
                    <input type="radio" id="enable" value="1" checked="checked" name="paylist_status" class="custom-control-input">

                    <label class="custom-control-label" for="customRadioInline2">Paid</label>
                    <input type="radio" id="disable" value="2" name="paylist_status" class="custom-control-input">
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

<div id="payroll_report" class="box">

    <div class="head"><h1 id="taxHeading"><?php echo"Pay List Report"; ?></h1></div>

    <div class="inner">
        <div class="top">

            <form id="printForm" action="../../../../services/salary/printing/payroll.php" method="post"
                  target="_blank">
                <input type="text" name="payroll_id" id="payroll" >

            </form>
        </div>
        <div id="Report"></div>

    </div>
</div>
<div id="salarylist" class="box">


    <div class="head"><h1 id="taxHeading"><?php echo "Company Pay List"; ?></h1></div>

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
                    <th rowspan="1" style="" >Allowance</th>
                    <th rowspan="1" style="" >Deduction</th>
                    <th rowspan="1" style="" >Status</th>
                    <th rowspan="1" style="" >Date Added</th>
                    <th rowspan="1" style="" >Perform By</th>

                </tr>
                </thead>



                <?php
                $connection = connect();

                $query = "SELECT l.id,l.status,l.date_added,e.emp_firstname,e.emp_lastname FROM ohrm_paylist l LEFT JOIN hs_hr_employee e  ON l.perform_by = e.emp_number";
                $result = mysqli_query($connection,$query) or die($connection);
                $num = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    ?>
                    <tr id="edit" class=" <?php echo (($num%2 == 0) ? 'even' : 'odd');?>">
                        <td><input type="checkbox" class="ohrmList_chkSelect" id="ohrmList_chkSelectRecord_2"  name="chkSelectRow[]"  data-payroll-id="<?php echo $row["id"]; ?>" /></td>
                        <td style="display: none;" class="left"  ><?php echo $row['id'];?></td>
                        <td class="left"  ><?php echo $row['allowance'];?></td>
                        <td class="left"  ><?php echo $row['deduction'];?></td>
                        <td class="left" ><?php echo (($row['status']) == '1' ? 'Generated' : 'Paid');?></td>
                        <td class="left" ><?php echo $row['date_added'];?></td>
                        <td class="left" ><?php echo $row['emp_firstname'].' '.$row['emp_lastname'];?></td>

                    </tr>

                    <?php $num--; } ?>

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

        $(".alert").fadeTo(2000,1).fadeOut(1000);


    });

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }



    $(document).on('click', '#ohrmList_chkSelectAll', function() {
        $(".ohrmList_chkSelect").prop("checked", this.checked);
        $("#select_count").html($("input.ohrmList_chkSelect:checked").length+" Selected");
    });
    $(document).on('click', '.ohrmList_chkSelect', function() {

        $("#select_count").html($("input.ohrmList_chkSelect:checked").length+" Selected");
    });

    // delete selected records
    $('#btnDelete').on('click', function(e) {
        var employee = [];
        $(`.ohrmList_chkSelect:checked`).each(function() {
            employee.push($(this).data('payroll-id'));
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
                    url: "../../../../services/salary/delete_payroll.php",
                    cache:false,
                    data: 'payroll_id='+selected_values,
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
        $('#payroll_report').hide();

        $('#payroll').hide();
        $(".otherSection").hide();
        $('#btnAdd').click(function() {
            $('#payroll').show();
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
            $('#payroll').hide();
            $('.top').show();
            $('#btnDelete').show();
            $('.error').hide();

        });

        $('#btnCancelReport').click(function() {
            $('#payroll_report').hide();

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
    // Print Selected Record
    $('#btnPrintAll').on('click', function(e) {
        var employee = [];
        $(`.ohrmList_chkSelect:checked`).each(function () {
            employee.push($(this).data('payroll-id'));
        });
        if (employee.length <= 0) {
            alert("Please select records.");
        } else {
            document.forms["printForm"]["payroll"].value = employee;
          $('#printForm').submit();
        }
    });
    function Validation() {
        var allowance = document.forms["frmTax"]["allowance"];
        var basic = document.forms["frmTax"]["basic"];
        var deduction = document.forms["frmTax"]["deduction"];
        var employee = document.forms["frmTax"]["employee_name"];
        var status = document.forms["frmTax"]["paylist_status"];
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


</script>


