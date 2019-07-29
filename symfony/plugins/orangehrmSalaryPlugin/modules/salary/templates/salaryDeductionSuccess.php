<?php


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

$fix_rate = 0;
$percentage = 0;
$status = 0;
$msg = "";
$max_amount = 0;
$min_amount = 0;
$year = 0;
$connection = connect();

if (isset( $_POST["btnSave"] ) && $_POST["btnSave"] == "Save") {

    if (isset( $_POST["deduction_status"] ) && ((int)$_POST["deduction_status"] == 1 || (int)$_POST["deduction_status"] == 2))
        $deduction_status = trim( $_POST["deduction_status"] );
    if (isset( $_POST["deduction"] ))
        $deduction = trim( $_POST["deduction"] );
    if (isset( $_POST["percentage"] ))
        $percentage = trim( $_POST["percentage"] );
    if (isset( $_POST["rate"] ))
        $fix_rate = trim( $_POST["rate"] );

    if ($deduction == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Deduction Name.</b>
			</div>';
    }
    if ($percentage == "" && $fix_rate == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Percentage or Fix Rate.</b>
			</div>';
    }


    if ($msg == "" && $_SESSION["msg"] == "") {
        $query = "INSERT INTO hs_hr_emp_deductions SET deduction_name = '" .( $deduction ) . "', percentage = '" . ( $percentage ) . "', rate = '". ( $fix_rate )."', status='" . (int)$deduction_status . "', perform_by = '" . $sf_user->getAttribute('auth.empNumber') . "', date_added=NOW()";
        mysqli_query( $connection, $query ) or die ( 'Could not insert deduction because: ' . mysqli_error( $connection ) );
        //echo $query;

        $_SESSION["msg"] ='<div class="message success fadable"> Successfully Saved<a href="#" class="messageCloseButton"></a></div>';

        header("Location:  ".$_SERVER['PHP_SELF']."", true);
    } else {
        header("Location:  ".$_SERVER['PHP_SELF']."", true);

    }

}


if (isset( $_POST["btnUpdate"] ) && $_POST["btnUpdate"] == "Update") {

    if (isset( $_POST["deduction_status"] ) && ((int)$_POST["deduction_status"] == 1 || (int)$_POST["deduction_status"] == 2))
        $deduction_status = trim( $_POST["deduction_status"] );
    if (isset( $_POST["deduction"] ))
        $deduction = trim( $_POST["deduction"] );
    if (isset( $_POST["percentage"] ))
        $percentage = trim( $_POST["percentage"] );
    if (isset( $_POST["rate"] ))
        $fix_rate = trim( $_POST["rate"] );
    if (isset( $_POST["id"] ))
        $id = trim( $_POST["id"] );

    if ($percentage == "" && $fix_rate=="") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter percentage or Fix Rate.</b>
			</div>';
    }

    if ($deduction == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Deduction Name.</b>
			</div>';
    }


    if ($msg == "" && $_SESSION["msg"] == "") {
        $query = "UPDATE hs_hr_emp_deductions SET deduction_name = '" .( $deduction ) . "', percentage = '" . ( $percentage ) . "', status='" . (int)$deduction_status . "', rate = '".$fix_rate."', perform_by = '" . $sf_user->getAttribute('auth.empNumber'). "', date_modified=NOW() WHERE id = '".$id."'";
        mysqli_query( $connection, $query ) or die ( 'Could not allowance because: ' . mysqli_error( $connection ) );
        //echo $query;

        $_SESSION["msg"] ='<div class="message success fadable"> Successfully Updated<a href="#" class="messageCloseButton"></a></div>';

        header("Location:  ".$_SERVER['PHP_SELF']."", true);
    } else {
        header("Location:  ".$_SERVER['PHP_SELF']."", true);

    }

}
?>
<?php if(!empty($statusMsg)){ ?>
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
<?php } ?>
<div id="deduction" class="box">
    <div class="head"><h1 id="taxHeading"><?php echo __("Deductions"); ?></h1></div>

    <div class="inner">


        <form name="frmDeduct" id="frmDeduct" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return Validation();" >

            <fieldset>
                <ol>
                    <li style="display: none">
                        <label for="tax_id">ID <em>*</em></label>
                        <input type="text" name="id" class="formInput" maxlength="100" id="deduction_id">
                        <span class="error error_min" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="">Deduction Name<em>*</em></label>
                        <input type="text" name="deduction" class="formInput" maxlength="100" id="deduction_name">
                        <span class="error error_min" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="">Percentage <em>*</em></label>
                        <input type="text" name="percentage" class="formInput" maxlength="100" id="percentage">
                        <span class="error error_max" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="">Fix Rate <em>(if any)</em></label>
                        <input type="text" name="rate" class="formInput" maxlength="100" id="rate">
                        <span class="error error_max" style="margin-left: 1%"></span>
                    </li>
                    <li class="radio">
                        <label for="">Status <em>*</em></label>

                        <ul class="radio_list">
                            <li><input name="deduction_status" type="radio" value="1" id="enable" class="editable" >&nbsp;<label for="">Enable</label></li>
                            <li><input name="deduction_status" type="radio" value="2" id="disable" class="editable">&nbsp;<label for="">Disable</label></li>
                        </ul>
                        <span class="error error_status" style="margin-left: 1%"></span>
                    </li>

                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>

                <p>
                    <input type="submit" class="savebutton" name="btnSave" id="btnSave" value="<?php echo __("Save"); ?>"/>

                    <input type="button" class="reset" name="btnCancel" id="btnCancel" value="<?php echo __("Cancel");?>"/>
                </p>
            </fieldset>
        </form>
    </div>
</div>

<div id="deductionlist" class="box">

    <div class="head"><h1 id="taxHeading"><?php echo __("Salary Deductions"); ?></h1></div>

    <div class="inner">
        <div class="top">


            <input type="button" class="" id="btnAdd" name="btnAdd" value="Add">
            <input type="submit" class="delete" id="btnDelete" name="btnDelete" value="Delete" data-toggle="modal" data-target="#deleteConfModal" disabled="disabled">

        </div>
        <div id="helpText" class="helpText"> <?php
            echo $msg;
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
                    <th rowspan="1" style="" class="header">Deduction</th>
                    <th rowspan="1" style="" class="header">Percentage</th>
                    <th rowspan="1" style="" class="header">Rate</th>
                    <th rowspan="1" style="" class="header">Status</th>
                    <th rowspan="1" style="" class="header">Date Added</th>
                    <th rowspan="1" style="" class="header">Date Modified</th>
                    <th rowspan="1" style="" class="header">Perform By</th>

                </tr>
                </thead>



                <?php
                $connection = connect();

                $query = "SELECT d.id,d.deduction_name,d.percentage,d.status,d.rate,d.date_added,d.date_modified,e.emp_firstname,e.emp_lastname FROM hs_hr_emp_deductions d LEFT JOIN hs_hr_employee e ON d.perform_by = e.emp_number WHERE d.id <> 0 ORDER BY d.percentage";
                $result = mysqli_query($connection,$query) or die($connection);
                $num = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    ?>


                    <tr id="edit" class=" <?php echo (($num%2 == 0) ? 'even' : 'odd');?>">
                        <td><input type="checkbox" class="ohrmList_chkSelect" id="ohrmList_chkSelectRecord_2"  name="chkSelectRow[]"  data-deduction-id="<?php echo $row["id"]; ?>" /></td>
                        <td style="display: none;" class="left"  ><?php echo $row['id'];?></td>
                        <td class="left"  ><?php echo $row['deduction_name'];?></td>
                        <td class="left" ><?php echo $row['percentage'];?> %</td>
                        <td class="left" ><?php echo $row['rate'];?> </td>
                        <td class="left" ><?php echo (($row['status']) == 1 ? 'Active' : 'Inactive');?></td>
                        <td class="left" ><?php echo $row['date_added'];?></td>
                        <td class="left" ><?php echo $row['date_modified'];?></td>
                        <td class="left" ><?php echo $row['emp_firstname'].' '.$row['emp_lastname'];?></td>

                    </tr>

                    <?php $num--; } ?>

            </table>
        </div> <!-- tableWrapper -->

    </div>
</div>
</div>
</div>
<!-- Confirmation box HTML: Begins -->
<div class="modal hide" id="deleteConfModal">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('OrangeHRM - Confirmation Required'); ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo __(CommonMessages::DELETE_CONFIRMATION); ?></p>
    </div>
    <div class="modal-footer">

        <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" name="bulk_delete_submit" value="<?php echo __('Ok'); ?>" />

        <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>
<!-- Confirmation box HTML: Ends -->
<script type="text/javascript">


    $(document).on('click', '#ohrmList_chkSelectAll', function() {
        $(".ohrmList_chkSelect").prop("checked", this.checked);
        $("#select_count").html($("input.ohrmList_chkSelect:checked").length+" Selected");
    });
    $(document).on('click', '.ohrmList_chkSelect', function() {

        $("#select_count").html($("input.ohrmList_chkSelect:checked").length+" Selected");
    });

    // delete selected records
    $('#dialogDeleteBtn').on('click', function(e) {
        var employee = [];
        $(".ohrmList_chkSelect:checked").each(function() {
            employee.push($(this).data('deduction-id'));
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
                    url: "../../../../services/salary/delete_deduction.php",
                    cache:false,
                    data: 'deduct_id='+selected_values,
                    success: function(response) {
// remove deleted employee rows

                        var emp_ids = response.split(",");
                        for (var i=0; i < emp_ids.length; i++ ) { $("#"+emp_ids[i]).remove(); }
                        location.reload();
                    } }); } } });



    $(document).ready(function() {

        $('#btnSave').click(function() {
            $('#frmDeduct').submit();
        });

        $('#deduction').hide();

        $('#btnAdd').click(function() {
            $('#deduction').show();
            $('.top').hide();
            $('.error').hide();
            $('#deduction_name').val('');
            $('#percentage').val('');
            $('#rate').val('');
            $('#personal_optGender_1').val('');
            $('#personal_optGender_2').val('');
            $(".messageBalloon_success").remove();
        });

        $('#btnCancel').click(function() {
            $('#deduction').hide();
            $('.top').show();
            $('#btnDelete').show();
            document.getElementById("btnSave").value = "Save";
            document.getElementById("btnSave").name = "btnSave";

        });


        $('#btnDelete').attr('disabled', 'disabled');

        $(':checkbox[name*="chkSelectRow[]"]').click(function() {
            if($(':checkbox[name*="chkSelectRow[]"]').is(':checked')) {
                $('#btnDelete').removeAttr('disabled');
            } else {
                $('#btnDelete').attr('disabled','disabled');
            }
        });

        $('#btnDelete').click(function(){
            $('#frmList_ohrmListComponent').submit(function(){
                $('#deleteConfirmation').dialog('open');
                return false;
            });
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

    $(document).ready(function(){
        $('tr .left').on('click',function(){
            $('#deduction').show();
            $('.top').hide();
            $('.error').hide();

            var table = document.getElementById('resultTable');


            for(var i = 0; i< table.rows.length; i++) {
                table.rows[i].onclick = function () {
                    document.getElementById("deduction_id").value = this.cells[1].innerHTML;
                    document.getElementById("deduction_name").value = this.cells[2].innerHTML;
                    document.getElementById("percentage").value = this.cells[3].innerHTML;
                    document.getElementById("rate").value = this.cells[4].innerHTML;
                    if(this.cells[7].innerHTML == "1"){
                        $('input[id=enable]').prop('checked', true);
                    }else{
                        $('input[id=disable]').prop('checked', true);

                    }
                }
            }
            document.getElementById("btnSave").value = "Update";
            document.getElementById("btnSave").name = "btnUpdate";
        });
    });

    function Validation() {
        var deduction = document.forms["frmDeduct"]["deduction"];
        var percentage = document.forms["frmDeduct"]["percentage"];
        var rate = document.forms["frmDeduct"]["rate"];


        var status = document.forms["frmDeduct"]["deduct_status"];

        if (deduction.value == "") {
            $('.error_min').html("Please enter deduction name.");
            $('.error_min').show();
            deduction.focus();
            return false;
        }else{
            $('.error_min').hide();
        }

        if (percentage.value == "" && rate.value == "") {
            $('.error_max').html("Please enter percentage or fix rate.");
            $('.error_max').show();
            percentage.focus();
            return false;
        }else{
            $('.error_max').hide();
        }


        if (status.value == "") {
            $('.error_status').html("Please select status.");
            $('.error_status').show();
            status.focus();
            return false;
        }else{
            $('.error_status').hide();
        }

        return true;

    }
</script>

