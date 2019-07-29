
<?php if(!empty($statusMsg)){ ?>
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
<?php } ?>
<div id="tax_slab" class="box">
    <div class="head"><h1 id="taxHeading"><?php echo __("Tax Calculator"); ?></h1></div>

    <div class="inner">


        <form name="frmTax" id="frmTax" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return Validation();" >

            <fieldset>
                <ol>
                    <li>
                        <label for="tax_id">Monthly Salary <em>*</em></label>
                        <input id="MonthlySalary" name="MonthlySalary" onkeyup="getCalculate(this.value)" placeholder="Enter Your Monthly Salary" class="formInput">

                    </li>


                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>


            </fieldset>
        </form>
    </div>
</div>

        <div id="tax_calc" class="box">

            <div class="head"><h1 id="taxHeading"><?php echo __("Tax Calculator"); ?></h1></div>

            <div class="inner">

                <div id="Income"></div>

        </div>
        </div>

<script>
    $('#tax_calc').hide();
    function getCalculate(val) {

        if (val == "") {
            document.getElementById("Income").innerHTML = "";
            $('#tax_calc').hide();
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

                    document.getElementById("Income").innerHTML = this.responseText;
                    $('#tax_calc').show();

                }else{
                    $('#tax_calc').hide();
                }
            };
            xmlhttp.open("GET","../../../../services/calculator.php?val="+val,true);
            xmlhttp.send();

        }
    }
</script>

