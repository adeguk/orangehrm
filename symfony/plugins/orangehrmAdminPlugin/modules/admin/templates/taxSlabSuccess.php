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

    if (isset( $_POST["tax_status"] ) && ((int)$_POST["status"] == 1 || (int)$_POST["tax_status"] == 2))
        $status = trim( $_POST["tax_status"] );
    if (isset( $_POST["year"] ))
        $year = trim( $_POST["year"] );
    if (isset( $_POST["min_amount"] ))
        $min_amount = trim( $_POST["min_amount"] );
    if (isset( $_POST["max_amount"] ))
        $max_amount = trim( $_POST["max_amount"] );
    if (isset( $_POST["fix_rate"] ))
        $fix_rate = trim( $_POST["fix_rate"] );
    if (isset( $_POST["percentage"] ))
        $percentage = trim( $_POST["percentage"] );

    if ($percentage == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter percentage.</b>
			</div>';
    }


    if ($fix_rate == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Tax Fix Rate.</b>
			</div>';
    }
    if ($max_amount == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Maximum Amount.</b>
			</div>';
    }
    if ($min_amount == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Minimum Amount.</b>
			</div>';
    }
    if ($year == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Select year.</b>
			</div>';
    }

    if ($msg == "" && $_SESSION["msg"] == "") {
        $query = "INSERT INTO ohrm_taxslab SET min_amount = '" .( $min_amount ) . "', max_amount = '" . ( $max_amount ) . "', fix_rate = '" . ( $fix_rate ) . "', percentage = '" . ( $percentage ) . "', year = '" . ( $year ) . "', status='" . (int)$status . "', perform_by = '" . $sf_user->getAttribute('auth.empNumber') . "', date_added=NOW()";
        mysqli_query( $connection, $query ) or die ( 'Could not insert tax slab because: ' . mysqli_error( $connection ) );
        //echo $query;

        $_SESSION["msg"] ='<div class="message success fadable"> Successfully Saved<a href="#" class="messageCloseButton"></a></div>';

        header("Location:  ".$_SERVER['PHP_SELF']."", true);
    } else {
        header("Location:  ".$_SERVER['PHP_SELF']."", true);

    }

}


if (isset( $_POST["btnUpdate"] ) && $_POST["btnUpdate"] == "Update") {

    if (isset( $_POST["tax_status"] ) && ((int)$_POST["status"] == 1 || (int)$_POST["tax_status"] == 2))
        $status = trim( $_POST["tax_status"] );
    if (isset( $_POST["year"] ))
        $year = trim( $_POST["year"] );
    if (isset( $_POST["min_amount"] ))
        $min_amount = trim( $_POST["min_amount"] );
    if (isset( $_POST["max_amount"] ))
        $max_amount = trim( $_POST["max_amount"] );
    if (isset( $_POST["fix_rate"] ))
        $fix_rate = trim( $_POST["fix_rate"] );
    if (isset( $_POST["percentage"] ))
        $percentage = trim( $_POST["percentage"] );
    if (isset( $_POST["id"] ))
        $id = trim( $_POST["id"] );
    if ($percentage == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter percentage.</b>
			</div>';
    }


    if ($fix_rate == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Tax Fix Rate.</b>
			</div>';
    }
    if ($max_amount == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Maximum Amount.</b>
			</div>';
    }
    if ($min_amount == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Enter Minimum Amount.</b>
			</div>';
    }
    if ($year == "") {
        $_SESSION["msg"] = '<div class="alert alert-danger alert-dismissable">
			<i class="fa fa-ban"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<b>Please Select year.</b>
			</div>';
    }

    if ($msg == "" && $_SESSION["msg"] == "") {
        $query = "UPDATE ohrm_taxslab SET min_amount = '" .( $min_amount ) . "', max_amount = '" . ( $max_amount ) . "', fix_rate = '" . ( $fix_rate ) . "', percentage = '" . ( $percentage ) . "', year = '" . ( $year ) . "', status='" . (int)$status . "', perform_by = '" . $sf_user->getAttribute('auth.empNumber') . "', date_modified=NOW() WHERE id = '".$id."'";
        mysqli_query( $connection, $query ) or die ( 'Could not update tax slab because: ' . mysqli_error( $connection ) );
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
<div id="tax_slab" class="box">
    <div class="head"><h1 id="taxHeading"><?php echo __("Tax Slab"); ?></h1></div>

    <div class="inner">


        <form name="frmTax" id="frmTax" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return Validation();" >

            <fieldset>
                <ol>
                    <li style="display: none">
                        <label for="tax_id">ID <em>*</em></label>
                        <input type="text" name="id" class="formInput" maxlength="100" id="tax_id">
                        <span class="error error_min" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="nationality_name">Min Amount <em>*</em></label>
                        <input type="text" name="min_amount" class="formInput" maxlength="100" id="min_amount">
                        <span class="error error_min" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="nationality_name">Max Amount <em>*</em></label>
                        <input type="text" name="max_amount" class="formInput" maxlength="100" id="max_amount">
                        <span class="error error_max" style="margin-left: 1%"></span>

                    </li>
                    <li>
                        <label for="nationality_name">Fix Rate <em>*</em></label>
                        <input type="text" name="fix_rate" class="formInput" maxlength="100" id="fix_rate">
                        <span class="error error_rate" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="nationality_name">Percentage <em>*</em></label>
                        <input type="text" name="percentage" class="formInput" maxlength="100" id="percentage">
                        <span class="error error_percent" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="nationality_name">Year <em>*</em></label>
                        <input type="text" name="year" class="formInput" maxlength="100" id="year">
                        <span class="error error_year" style="margin-left: 1%"></span>
                    </li>
                    <li class="radio">
                        <label for="tax_status">Status <em>*</em></label>

                        <ul class="radio_list">
                            <li><input name="tax_status" type="radio" value="1" id="enable" class="editable" >&nbsp;<label for="personal_optGender_1">Enable</label></li>
                            <li><input name="tax_status" type="radio" value="2" id="disable" class="editable">&nbsp;<label for="personal_optGender_2">Disable</label></li>
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

        <div id="tax_slablist" class="box">

            <div class="head"><h1 id="taxHeading"><?php echo __("Tax Slab"); ?></h1></div>

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
                        <th rowspan="1" style="" class="header">Min Amount</th>
                        <th rowspan="1" style="" class="header">Max Amount</th>
                        <th rowspan="1" style="" class="header">Fix Rate</th>
                        <th rowspan="1" style="" class="header">Percentage</th>
                        <th rowspan="1" style="" class="header">Year</th>
                        <th rowspan="1" style="" class="header">Status</th>
                        <th rowspan="1" style="" class="header">DateAdded</th>
                        <th rowspan="1" style="" class="header">DateModified</th>
                        <th rowspan="1" style="" class="header">Perform By</th>

                    </tr>
                    </thead>



                    <?php
                    $connection = connect();

                    $query = "SELECT t.id,t.year,t.min_amount,t.max_amount,t.fix_rate,t.percentage,t.status,t.date_added,t.date_modified,e.emp_firstname,e.emp_lastname FROM ohrm_taxslab t LEFT JOIN hs_hr_employee e ON t.perform_by = e.emp_number WHERE t.id <> 0 ORDER BY min_amount";
                    $result = mysqli_query($connection,$query) or die($connection);
                    $num = mysqli_num_rows($result);
                    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    ?>


                    <tr id="edit" class=" <?php echo (($num%2 == 0) ? 'even' : 'odd');?>">
                        <td><input type="checkbox" class="ohrmList_chkSelect" id="ohrmList_chkSelectRecord_2"  name="chkSelectRow[]"  data-tax-id="<?php echo $row["id"]; ?>" /></td>
                        <td style="display: none;" class="left"  ><?php echo $row['id'];?></td>
                        <td class="left"  ><?php echo $row['min_amount'];?></td>
                        <td class="left" ><?php echo $row['max_amount'];?></td>
                        <td class="left" ><?php echo $row['fix_rate'];?></td>
                        <td class="left" ><?php echo $row['percentage'];?> %</td>
                        <td class="left" ><?php echo $row['year'];?></td>
                        <td class="left" ><?php echo $row['status'];?></td>
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
            employee.push($(this).data('tax-id'));
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
                    url: "../../../../services/delete_action.php",
                    cache:false,
                    data: 'emp_id='+selected_values,
                    success: function(response) {
// remove deleted employee rows

                        var emp_ids = response.split(",");
                        for (var i=0; i < emp_ids.length; i++ ) { $("#"+emp_ids[i]).remove(); }
                        location.reload();
                    } }); } } });



    $(document).ready(function() {

        $('#btnSave').click(function() {
            $('#frmTax').submit();
        });

        $('#tax_slab').hide();

        $('#btnAdd').click(function() {
            $('#tax_slab').show();
            $('.top').hide();
            $('.error').hide();
            $('#min_amount').val('');
            $('#max_amount').val('');
            $('#year').val('');
            $('#fix_rate').val('');
            $('#percentage').val('');
            $('#personal_optGender_1').val('');
            $('#personal_optGender_2').val('');
            $(".messageBalloon_success").remove();
        });

        $('#btnCancel').click(function() {
            $('#tax_slab').hide();
            $('.top').show();
            $('#btnDelete').show();

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
            $('#tax_slab').show();
            $('.top').hide();
            $('.error').hide();

            var table = document.getElementById('resultTable');


            for(var i = 0; i< table.rows.length; i++) {
               table.rows[i].onclick = function () {
                   document.getElementById("tax_id").value = this.cells[1].innerHTML;
                   document.getElementById("min_amount").value = this.cells[2].innerHTML;
                   document.getElementById("max_amount").value = this.cells[3].innerHTML;
                   document.getElementById("fix_rate").value = this.cells[4].innerHTML;
                   document.getElementById("percentage").value = this.cells[5].innerHTML;
                   document.getElementById("year").value = this.cells[6].innerHTML;
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
        var min_amount = document.forms["frmTax"]["min_amount"];
        var max_amount = document.forms["frmTax"]["max_amount"];
        var percentage = document.forms["frmTax"]["percentage"];
        var year = document.forms["frmTax"]["year"];
        var fix_rate = document.forms["frmTax"]["fix_rate"];
        var status = document.forms["frmTax"]["tax_status"];

        if (min_amount.value == "") {
            $('.error_min').html("Please enter minimum amount.");
            $('.error_min').show();
            min_amount.focus();
            return false;
        }else{
            $('.error_min').hide();
        }

        if (max_amount.value == "") {
            $('.error_max').html("Please enter maximum amount.");
            $('.error_max').show();
            max_amount.focus();
            return false;
        }else{
            $('.error_max').hide();
        }

        if (percentage.value == "") {
            $('.error_percent').html("Please enter percentage.");
            $('.error_percent').show();
            percentage.focus();
            return false;
        }else{
            $('.error_percent').hide();
        }

        if (year.value == "") {
            $('.error_year').html("Please enter year.");
            $('.error_year').show();
            year.focus();
            return false;
        }else{
            $('.error_year').hide();
        }

        if (fix_rate.value == "") {
            $('.error_rate').html("Please enter fix rate.");
            $('.error_rate').show();
            fix_rate.focus();
            return false;
        }else{
            $('.error_rate').hide();
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

