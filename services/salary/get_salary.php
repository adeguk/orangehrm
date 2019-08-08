<?php

define("DB_HOST", "localhost");
define("DB_NAME", "career.loggcity");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "thir3a6-i");

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
$deduction = array();
$other_allowance_amount = array();
$other_allowance = array();
$id = intval($_GET['id']);
$salary_deduction = array();

$query = "SELECT s.status,e.emp_firstname,e.emp_lastname,e.emp_other_name,e.emp_other_amount,s.employee_id,s.basic,s.transport,s.house_rent,s.utilities,s.medical,s.date_added FROM hs_hr_emp_salarybreak_allowance s LEFT JOIN hs_hr_employee e ON s.employee_id = e.emp_number WHERE s.id = ".$id."";
$result = mysqli_query($connection,$query);
$grand_total = 0;
$net_payment=0;
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
    $employee_name = $row["emp_firstname"].' '.$row["emp_lastname"];
    $status = $row["status"];
    $basic = $row["basic"];
    $employee_id = $row['employee_id'];
    $transport = $row["transport"];
    $house_rent = $row["house_rent"];
    $transport = $row["transport"];
    $utilities = $row["utilities"];
    $medical = $row["medical"];
    $medical = $row["medical"];
    $date_added = $row["date_added"];
    if($row['emp_other_name']!="" && $row['emp_other_amount'])
    {
        $other_allowance = explode(',',$row['emp_other_name']);
        $other_allowance_amount = explode(',',$row['emp_other_amount']);
    }

    $grand_total = $basic+$transport+$utilities+$medical+$house_rent;
}


$query3 = "SELECT allowance_id,deduction_id FROM hs_hr_emp_salarybreak_allowance WHERE id = ".$id." ";
$result3 = mysqli_query($connection,$query3);
while($row3 = mysqli_fetch_assoc($result3)) {

    $allowance_id = $row3["allowance_id"];
    $deduction_id = $row3["deduction_id"];
    $allowance_id = explode(",",$allowance_id);

}
$query7 = "SELECT deduction_name FROM hs_hr_emp_deductions WHERE id <> 0 AND status = 1 ";
$result7 = mysqli_query($connection,$query7);
while($row7 = mysqli_fetch_assoc($result7)){
$deduction_real[] = $row7['deduction_name'];
}
for($i=0;$i<sizeof($allowance_id);$i++) {
    $query4 = "SELECT a.allowance FROM hs_hr_emp_allowances a,hs_hr_emp_salarybreak_allowance s WHERE s.id =" . $id . " AND a.id = (CAST(" . $allowance_id[$i] . " AS INT))";
    $result4 = mysqli_query( $connection, $query4 );
    $row4 = mysqli_fetch_array( $result4,MYSQLI_ASSOC);
    $allowances[] = ($row4['allowance']);
}


$columns = mysqli_query( $connection, "show columns from hs_hr_emp_salarybreak_allowance" );
while ($c = mysqli_fetch_assoc( $columns )) {
    $feild[] = $c['Field'];
}

$deduction_columns = mysqli_query( $connection, "show columns from hs_hr_emp_salarybreak_deduction" );
while ($c = mysqli_fetch_assoc( $deduction_columns )) {
    $deduction_feild[] = $c['Field'];
}
if($deduction_id != 0) {
    for ($k = 0; $k < sizeof( $deduction_real ); $k++) {
        $deduction_exists = false;
        $column = strtolower( str_replace( ' ', '_', $deduction_real[$k] ) );
        if ($deduction_feild[$k + 1] == '' . $column . '') {
            $deduction_exists = true;
        }
        if ($deduction_exists) {
            $query6 = "SELECT $column FROM hs_hr_emp_salarybreak_deduction WHERE id =" . $deduction_id . " ";
            $result6 = mysqli_query( $connection, $query6 ) or die( 'could not fetch: ' . mysqli_error( $connection ) );
            $row6 = mysqli_fetch_assoc( $result6 );
            if($row6[$column] != null){
                $salary_deduction[] = $row6[$column];
            }
        }

    }
}


for ($i = 0; $i < sizeof( $allowance_id ); $i++) {
    $exists = false;
    $keys = (array_keys( $allowances));
    $column = strtolower( str_replace( ' ', '_', $allowances[$i] ) );

    for ($j = 0; $j < sizeof( $feild ); $j++) {

        if ($feild[$j] == $column) {
            $exists = true;
            $keys_reverse = ucfirst( str_replace( '_', ' ', $allowances[$i] ) );
            $query5 = "SELECT $feild[$j] FROM hs_hr_emp_salarybreak_allowance s WHERE s.id =".$id."";
            $result5 = mysqli_query( $connection, $query5 );
            $row5 = mysqli_fetch_array($result5,MYSQLI_ASSOC);
            $salary_allowance[] = ($row5[$feild[$j]]);
        }
    }
}
if(sizeof($other_allowance)>0) {

    for ($i = 0; $i < sizeof( $other_allowance_amount ); $i++) {
        $grand_total += $other_allowance_amount[$i];
    }


}
$net_payment = $grand_total;
if(sizeof($salary_deduction) > 0){
    for($h=0;$h<sizeof($salary_deduction);$h++){
        $net_payment -= $salary_deduction[$h];
    }
}




echo '
        <div id="scrollWrapper">
            <div id="scrollContainer">
            </div>
        </div>


        <div id="tableWrapper">
            <div class="top">

                <h2 class="employee" style="text-align: center;">'.strtoupper($employee_name).'</h2>
            </div>

        </div>
        <table class="table hover" style="width: 50%; float: left" id="resultTable">

            <thead>
            <th rowspan="1" colspan="2" style="text-align: center" class="header">Gross Slary</th>
            
           <tr>';
$k=0;
            foreach ($allowances as $allo) {
                echo '
             <tr>
             <th rowspan="1" style="" class="header">' . $allo . '</th>
                <td id="basic">' . $salary_allowance[$k] . '</td>
            ';

            $k++;
            }
echo '</tr>
</thead>
</table>
 <table style="width: 50%;" class="table hover" id="resultTable">
 <thead>
            <th rowspan="1" colspan="2" style="text-align: center" class="header">Other Allowance</th>
<tr>
';
   if(sizeof($other_allowance) > 0) {

                    for ($j = 0; $j < sizeof( $other_allowance ); $j++) {
                        echo'<th rowspan="1" style="" class="header"> '.$other_allowance[$j].'</th>
                <td> '.$other_allowance_amount[$j] .' </td>';
                        if ($other_allowance > 0) {
                            echo ' </tr>';
                        }

                    }
                }

echo '
</tr>
</thead>
</table>';

              echo'
            <table class="table hover">
         <thead>    
            <th rowspan="1" colspan="4" style="text-align: center">Grand Total: <span id="grand_total" style="font-weight: bold; font-size: 15px;">'.$grand_total.'</span></th>
            </thead>

        </table>
        <div class="top">
        </div>
        <div style="float: left; width: 50%;">
            <table class="table hover" id="resultTable">

                <thead>
                <th rowspan="1" colspan="2" style="text-align: center" class="header">Deductions</th>
                
                ';
if(sizeof($salary_deduction) > 0) {

    for ($j = 0; $j < sizeof( $salary_deduction); $j++) {
        if($salary_deduction[$j]>0) {

            echo '<tr><th rowspan="1" style="" class="header"> ' . $deduction_real[$j] . '</th>
                <td> ' . $salary_deduction[$j] . ' </td>';
            if ($salary_deduction > 0) {
                echo ' </tr>';
            }
        }

    }
}
echo' 

             

                </thead>

            </table>
        </div>
        <div style="float: left; width: 40%; margin-left: 4%" >
            <h2>Net Monthly Payment: <span style="font-size: 25px; color: #2ca02c;"> '.$net_payment.' </span></h2>
        </div>

';

?>
