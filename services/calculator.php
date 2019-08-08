
<?php

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
$date =  date("Y");
$result = 0;
$taxamount= 0;
$percentamount = 0;
$ExtraAmount=0;
$Min = 0;
$Max = 0;
$Maxpercentage=0;
$monthlytax = 0;

$Salary = intval($_GET['val']);
$yearlyincome = $Salary*12;


    $query1 = "SELECT MIN(min_amount) AS Minimum, MAX(min_amount) AS Maximum FROM ohrm_taxslab WHERE id <> 0 AND status = 1 AND year = '".$date."'";
    $results1 = mysqli_query($connection,$query1);
    $row1 = mysqli_fetch_array($results1);
	$Min = $row1["Minimum"];
	$Max = $row1["Maximum"];

    $query2 = "SELECT percentage,fix_rate FROM ohrm_taxslab WHERE id <> 0 AND status = 1 AND year = '".$date."' AND min_amount = '".$Max."'";
    $results2 = mysqli_query($connection,$query2);
  	$row2 = mysqli_fetch_array($results2);
    $Maxpercentage = $row2["percentage"];
    $Maxfix_rate = $row2["fix_rate"];

    $query3 = "SELECT MAX(max_amount) AS Maximum FROM ohrm_taxslab WHERE id <> 0 AND status = 1 AND percentage = 0 AND year = '".$date."'";
    $results3 = mysqli_query($connection,$query3);
    $row3 = mysqli_fetch_array($results3);
    $Maxofperzero = $row3["Maximum"];

    $query = "SELECT min_amount,max_amount,fix_rate,percentage FROM ohrm_taxslab WHERE id <> 0 AND status = 1 AND year = '".$date."'";
    $results = mysqli_query($connection,$query);

    while ($row = mysqli_fetch_array($results,MYSQLI_ASSOC))
    {

    	$min_amount = $row["min_amount"];
    	$max_amount = $row["max_amount"];
    	$fix_rate = $row["fix_rate"];
    	$percentage = $row["percentage"];


    	if ($yearlyincome > $min_amount && $yearlyincome <= $max_amount ) {

    		if($yearlyincome > $Maxofperzero)
    		{
    							$ExtraAmount = $yearlyincome-$row["min_amount"];
					    		$percentamount = ($ExtraAmount/100)*$row["percentage"];
					    		$IncomeTax = $row["fix_rate"] + $percentamount;

					    		$monthlytax = $percentamount/12;
					    		$MonthlySalaryAfterTax = $Salary-$monthlytax;
					    		$EarlySalaryAfterTax = $MonthlySalaryAfterTax*12;
					    		$fix_rate = $monthlytax*12;

					    							    	}
					    	else if($yearlyincome > $min_amount && $yearlyincome <= $Maxofperzero)
					    	{

					    		$monthlytax = ($fix_rate)/12;
					    		$monthlytax = number_format($monthlytax,2);
					    		$MonthlySalaryAfterTax = $Salary-$monthlytax;
					    		$EarlySalaryAfterTax = $MonthlySalaryAfterTax*12;

					    	}


    								 echo '
    								 <hr/>
    								 
    								 <fieldset>
                <ol>
                    <li>
                       <label  style="font-size: 20px; color: #000000;">Monthly Income</label>
                    </li>
                    <li><label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$Salary.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Monthly Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$monthlytax.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Salary After Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$MonthlySalaryAfterTax.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Yearly Income</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$yearlyincome.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Yearly Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$fix_rate.'</label>
</li>
                    <hr/>
                    <li>
                       <label style="font-size: 20px; color: #000000;">Yearly Income After Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.round($EarlySalaryAfterTax).'</label>
</li>
                    <hr/>
                </ol>


            </fieldset>';
						break;



    	}

    		if($yearlyincome >= $Max)
    	{
								$ExtraAmount = $yearlyincome-$Max;
					    		$percentamount = ($ExtraAmount/100)*$Maxpercentage;

					    		$fix_rate = $percentamount+$Maxfix_rate;
					    		$monthlytax = $fix_rate/12;
					    		$MonthlySalaryAfterTax = $Salary-$monthlytax;
					    		$EarlySalaryAfterTax = ($MonthlySalaryAfterTax*12);
					    		$monthlytax = number_format($monthlytax,2);




    								 echo '
    									 <hr/>
    								 
    								 <fieldset>
                <ol>
                    <li>
                       <label  style="font-size: 20px; color: #000000;">Monthly Income</label>
                    </li>
                    <li><label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$Salary.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Monthly Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$monthlytax.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Salary After Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$MonthlySalaryAfterTax.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Yearly Income</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$yearlyincome.'</label>
</li>
                    <hr/>
                   <li>
                       <label style="font-size: 20px; color: #000000;">Yearly Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.$fix_rate.'</label>
</li>
                    <hr/>
                    <li>
                       <label style="font-size: 20px; color: #000000;">Yearly Income After Tax</label>
                    </li>
                    <li>                        <label style="margin-left: 25%; font-size: 20px; color: #459e00;">'.round($EarlySalaryAfterTax).'</label>
</li>
                    <hr/>
                </ol>


            </fieldset>';

											break;
		}

  	}


 ?>

