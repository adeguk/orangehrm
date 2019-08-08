<?php
define( "DB_NAME", "career.loggcity " );
define( "DB_HOST", "localhost" );
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

$extensions= array("jpeg","jpg","png");
$msg = "";


if (isset( $_POST["btnSave"] ) && $_POST["btnSave"] == "Save") {

    if (isset( $_POST["certificate_status"] ) && ((int)$_POST["certificate_status"] == 1 || (int)
            $_POST["certificate_status"] == 2))
        $certificate_status = trim( $_POST["certificate_status"] );
    if (isset( $_POST["file_name"] ))
        $name = trim( $_POST["file_name"] );

    if (isset( $_POST["description"] ))
        $description = trim( $_POST["description"] );

    if ($name == "") {
        $_SESSION["msg"] = '<div class="message danger fadable"> Please Enter Name<a href="#" class="messageCloseButton"></a></div>';
    }
    if ($description == "") {
        $_SESSION["msg"] = '<div class="message danger fadable"> Please Enter Description<a href="#" class="messageCloseButton"></a></div>';
    }

    if ($msg == "" && $_SESSION["msg"] == "") {


        if(isset($_FILES['image'])) {
            $errors = array();
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $ext = explode('.', $_FILES['image']['name']);
            $file_ext = strtolower(end($ext));
            $imageProperties = getimageSize($_FILES['image']['tmp_name']);
            $imgData =addslashes(file_get_contents($_FILES['image']['tmp_name']));
            $imageSize = $_FILES['image']['size'];

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be exactely 2 MB';
            }
            if(isEmpty($errors)) {

                $query = "INSERT INTO ohrm_certificates SET name = '" . ($name) . "', description = '" . ($description) . "', file_name = '" . ($file_name) . "',file_size= '" . $imageSize . "',file_type = '" . $imageProperties['mime'] . "' ,file_data='" . $imgData . "', extension = '" . ($file_ext) . "', status='" . (int)$certificate_status . "', perform_by = '" . $sf_user->getAttribute('auth.empNumber') . "', date_added=NOW()";
                mysqli_query($connection, $query) or die ('Could not insert certificate because: ' . mysqli_error($connection));

                $_SESSION["msg"] = '<div class="message success fadable"> Successfully Saved<a href="#" class="messageCloseButton"></a></div>';

                header("Location:  " . $_SERVER['PHP_SELF'] . "", true);
            }
            }else{

                $_SESSION["msg"] = '<div class="message danger fadable"> Error in submitting file.<a href="#" class="messageCloseButton"></a></div>';

            }

    } else {
        header("Location:  ".$_SERVER['PHP_SELF']."", true);

    }

}

if (isset( $_POST["btnUpdate"] ) && $_POST["btnUpdate"] == "Update") {

    if (isset( $_POST["certificate_status"] ) && ((int)$_POST["certificate_status"] == 1 || (int)
            $_POST["certificate_status"] == 2))
        $certificate_status = trim( $_POST["certificate_status"] );
    if (isset( $_POST["file_name"] ))
        $name = trim( $_POST["file_name"] );

    if (isset( $_POST["description"] ))
        $description = trim( $_POST["description"] );

    if (isset( $_POST["id"] ))
        $id = trim( $_POST["id"] );

    if ($name == "") {
        $_SESSION["msg"] = '<div class="message danger fadable"> Please Enter Name<a href="#" class="messageCloseButton"></a></div>';
    }
    if ($description == "") {
        $_SESSION["msg"] = '<div class="message danger fadable"> Please Enter Description<a href="#" class="messageCloseButton"></a></div>';
    }


    if ($msg == "" && $_SESSION["msg"] == "") {
        if (isset($_FILES['image'])) {

            $errors = array();
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $ext = explode('.', $_FILES['image']['name']);
            $file_ext = strtolower(end($ext));
            $imageProperties = getimageSize($_FILES['image']['tmp_name']);
            $imgData =addslashes(file_get_contents($_FILES['image']['tmp_name']));
            $imageSize = $_FILES['image']['size'];

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be exactely 2 MB';
            }
            if(isEmpty($errors)){


            $query = "UPDATE ohrm_certificates SET name = '" . ($name) . "', description = '" . ($description) . "', file_name = '" . ($file_name) . "',file_size= '".$imageSize."',file_type = '".$imageProperties['mime']."' ,file_data='".$imgData."', extension = '" . ($file_ext) . "', status='" . (int)$certificate_status . "', perform_by = '" . $sf_user->getAttribute('auth.empNumber') . "', date_updated=NOW() WHERE id = ".$id." ";

                mysqli_query($connection, $query) or die ('Could not allowance because: ' . mysqli_error($connection));
                //echo $query;

                $_SESSION["msg"] = '<div class="message success fadable">Successfully Updated<a href="#" class="messageCloseButton"></a></div>';

                header("Location:  " . $_SERVER['PHP_SELF'] . "", true);
            }
        }
    }
else {
        header("Location:  ".$_SERVER['PHP_SELF']."", true);

    }

}


function download_file($id){
    $connection = connect();
    $sql = "SELECT file_type,file_data,file_name,extension,file_size FROM ohrm_certificates WHERE id=" . $id;

    $result = mysqli_query($connection, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($connection));
    $row = mysqli_fetch_array($result);
    header("Content-type: " . $row["file_type"]);
    header('Content-Disposition: attachment; filename="'.$row['file_name'].'"');
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0');
    header('Pragma: no-cache');
    header("Content-Length: ".$row['file_size']);
    return $row["file_data"];
}

?>
<?php if(!empty($statusMsg)){ ?>
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
<?php } ?>
<div id="certificate" class="box">
    <div class="head"><h1 id="taxHeading"><?php echo __("Certificate of NTN"); ?></h1></div>

    <div class="inner">
        <form name="frmCertificate" id="frmCertificate" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"
              onsubmit="return Validation();" enctype="multipart/form-data" >

            <fieldset>
                <ol>
                    <li style="display: none">
                        <label for="tax_id">ID <em>*</em></label>
                        <input type="text" name="id" class="formInput" maxlength="100" id="certi_id">

                    </li>
                    <li>
                        <label for="">Name<em>*</em></label>
                        <input type="text" name="file_name" class="formInput" id="file_name">
                        <span class="error error_name" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="file">File <em>*</em></label>
                        <span><input type="file" name="image" class="formInput" maxlength="100" id="file"> </span>
                        <span class="error error_file" style="margin-left: 1%"></span>
                    </li>
                    <li>
                        <label for="">Description <em>(if any)</em></label>
                        <textarea name="description" class="formInput" maxlength="100" id="description"></textarea>
                        <span class="error error_description" style="margin-left: 1%"></span>
                    </li>
                    <li class="radio">
                        <label for="">Status <em>*</em></label>

                        <ul class="radio_list">
                            <li><input name="certificate_status" type="radio" value="1" id="enable" class="editable"
                                >&nbsp;<label for="">Enable</label></li>
                            <li><input name="certificate_status" type="radio" value="2" id="disable"
                                       class="editable">&nbsp;<label for="">Disable</label></li>
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

<div id="certificatelist" class="box">

    <div class="head"><h1 id="taxHeading"><?php echo __("NTN Certificates"); ?></h1></div>

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
                    <th rowspan="1" style="" class="header">Name</th>
                    <th rowspan="1" style="" class="header">File Name</th>
                    <th rowspan="1" style="" class="header">Extension</th>
                    <th rowspan="1" style="" class="header">Description</th>
                    <th rowspan="1" style="" class="header">Status</th>
                    <th rowspan="1" style="" class="header">Preview</th>
                    <th rowspan="1" style="" class="header">Date Added</th>
                    <th rowspan="1" style="" class="header">Date Modified</th>
                    <th rowspan="1" style="" class="header">Perform By</th>

                </tr>
                </thead>



                <?php

                $connection = connect();
                $query = "SELECT c.id,c.name,c.file_name,c.extension,c.description,c.status,c.date_added,c.date_modified,e.emp_firstname,e.emp_lastname FROM ohrm_certificates c LEFT JOIN hs_hr_employee e ON c.perform_by = e.emp_number WHERE c.id <> 0 AND c.file_name LIKE '%ntn%' OR c.description LIKE '%ntn%' OR c.name LIKE '%ntn%' ";
                $result = mysqli_query($connection,$query) or die($connection);
                $num = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    ?>


                    <tr id="edit" class=" <?php echo (($num%2 == 0) ? 'even' : 'odd');?>">
                        <td><input type="checkbox" class="ohrmList_chkSelect" id="ohrmList_chkSelectRecord_2"  name="chkSelectRow[]"  data-certificate-id="<?php echo $row["id"]; ?>" /></td>
                        <td style="display: none;" class="left"  ><?php echo $row['id'];?></td>
                        <td class="left" ><?php echo $row['name'];?></td>
                        <td class="left" ><?php echo $row['file_name'];?></td>
                        <td class="left" ><?php echo $row['extension'];?> </td>
                        <td class="left" ><?php echo $row['description'];?> </td>
                        <td class="left" ><?php echo (($row['status']) == 1 ? 'Active' : 'Inactive');?></td>
                        <td class="left" ><?php echo (($row['file_name']) !="" &&  (in_array($row["extension"], $extensions) === true)? '<img title="Right Click And Click Save As For Download" width="60" height="60" src="../../../../services/certificate/fileView.php?id='.$row["id"].'"' : "<a href='' onclick='".download_file("".$row['id']."")."'>".$row["file_name"]."</a>"); ?></td>
                        <td class="left" ><?php echo $row['date_added'];?></td>
                        <td class="left" ><?php echo $row['date_modified'];?></td>
                        <td class="left" ><?php echo $row['emp_firstname'].' '.$row['emp_lastname'];?></td>
                        </td>

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
    $('#dialogDeleteBtn').on('click', function(e) {
        var employee = [];
        $(".ohrmList_chkSelect:checked").each(function() {
            employee.push($(this).data('certificate-id'));
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
                    url: "../../../../services/certificate/delete_certificate.php",
                    cache:false,
                    data: 'certi_id='+selected_values,
                    success: function(response) {
                        var emp_ids = response.split(",");
                        for (var i=0; i < emp_ids.length; i++ ) { $("#"+emp_ids[i]).remove(); }

                        if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                        }
                    } }); } } });



    $(document).ready(function() {

        $('#btnSave').click(function() {
            $('#frmCertificate').submit();
        });

        $('#certificate').hide();

        $('#btnAdd').click(function() {
            $('#certificate').show();
            $('.top').hide();
            $('.error').hide();
            $('#certificate_name').val('');
            $('#percentage').val('');
            $('#rate').val('');
            $('#personal_optGender_1').val('');
            $('#personal_optGender_2').val('');
            $(".messageBalloon_success").remove();
        });

        $('#btnCancel').click(function() {
            $('#certificate').hide();
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
            $('#certificate').show();
            $('.top').hide();
            $('.error').hide();

            var table = document.getElementById('resultTable');

            var destination = "<?php echo $destination; ?>";
            for(var i = 0; i< table.rows.length; i++) {
                table.rows[i].onclick = function () {
                    document.getElementById("certi_id").value = this.cells[1].innerHTML;
                    document.getElementById("file_name").value = this.cells[2].innerHTML;
                    document.getElementById("description").innerText = this.cells[5].innerHTML;

                    if(this.cells[6].innerHTML == "Active"){
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
        var name = document.forms["frmCertificate"]["name"];
        var file_name = document.forms["frmCertificate"]["file_name"];
        var description = document.forms["frmCertificate"]["description"];


        var status = document.forms["frmCertificate"]["certificate_status"];

        if (name.value == "") {
            $('.error_name').html("Please enter file name.");
            $('.error_name').show();
            name.focus();
            return false;
        }else{
            $('.error_min').hide();
        }

        if (file_name.value == "") {
            $('.error_file').html("Please enter file");
            $('.error_file').show();
            file_name.focus();
            return false;
        }else{
            $('.error_max').hide();
        }
        if (description.value == "") {
            $('.error_description').html("Please enter description.");
            $('.error_description').show();
            description.focus();
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
