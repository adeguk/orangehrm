<?php
require('fpdf17/fpdf.php');

define( "DB_HOST", "localhost" );
define( "DB_NAME", "career.loggcity" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "thir3a6-i" );


setlocale(LC_MONETARY,"en_US");

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
function array_mesh() {
	// Combine multiple associative arrays and sum the values for any common keys
	// The function can accept any number of arrays as arguments
	// The values must be numeric or the summed value will be 0

	// Get the number of arguments being passed
	$numargs = func_num_args();

	// Save the arguments to an array
	$arg_list = func_get_args();

	// Create an array to hold the combined data
	$out = array();

	// Loop through each of the arguments
	for ($i = 0; $i < $numargs; $i++) {
		$in = $arg_list[$i]; // This will be equal to each array passed as an argument

		// Loop through each of the arrays passed as arguments
		foreach($in as $key => $value) {
			// If the same key exists in the $out array
			if(array_key_exists($key, $out)) {
				// Sum the values of the common key
				$sum = $in[$key] + $out[$key];
				// Add the key => value pair to array $out
				$out[$key] = $sum;
			}else{
				// Add to $out any key => value pairs in the $in array that did not have a match in $out
				$out[$key] = $in[$key];
			}
		}
	}

	return $out;
}

$pdf = new FPDF('P', 'mm', 'A4');

//get invoices data

$payroll = trim($_POST['payroll_id']);


$payroll_id = explode(',',$payroll);

$month = "";
$year = "";
$date = "";
$employee = "";


$query7 = "SELECT salary_id FROM hs_hr_payroll WHERE id in ('".$payroll."') ";
$result7 = mysqli_query($connection, $query7);
while ($row7 = mysqli_fetch_assoc($result7)) {
	$employee = $row7['salary_id'];
}
$emp_array = explode(',',$employee);

	$query7 = "SELECT deduction_name FROM hs_hr_emp_deductions WHERE id <> 0 AND status = 1 ";
	$result7 = mysqli_query($connection, $query7);
	while ($row7 = mysqli_fetch_assoc($result7)) {
		$deduction_real[] = $row7['deduction_name'];
	}

$query3 = "SELECT allowance_id FROM hs_hr_emp_salarybreak_allowance WHERE id = " . $emp_array[0] . " ";
$result3 = mysqli_query($connection, $query3);
while ($row3 = mysqli_fetch_assoc($result3)) {

	${'allowance_id'} = $row3["allowance_id"];

	${'allowance_id'} = explode(",", ${'allowance_id'});

}
for ($i = 0; $i < sizeof(${'allowance_id'}); $i++) {
	$query4 = "SELECT a.allowance FROM hs_hr_emp_allowances a,hs_hr_emp_salarybreak_allowance s WHERE s.id =" . $emp_array[0] . " AND a.id = (CAST(" . ${'allowance_id'}[$i] . " AS INT))";
	$result4 = mysqli_query($connection, $query4);
	$row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
	${'allowances'}[] = ($row4['allowance']);

}


$columns = mysqli_query($connection, "show columns from hs_hr_emp_salarybreak_allowance");
while ($c = mysqli_fetch_assoc($columns)) {
	${'feild'}[] = $c['Field'];
}


for ($i = 0; $i < sizeof(${'allowance_id'}); $i++) {
	$exists = false;
	$keys = (array_keys(${'allowances'}));
	${'column'} = strtolower(str_replace(' ', '_', ${'allowances'}[$i]));

	for ($j = 0; $j < sizeof(${'feild'}); $j++) {

		if (${'feild'}[$j] == ${'column'}) {
			$exists = true;
			${'keys_reverse'}[] = ucfirst(str_replace('_', ' ', ${'allowances'}[$i]));
		}

	}
}
$allowance_real = ${'keys_reverse'};
$query = "SELECT s.date_added FROM hs_hr_emp_salarybreak_allowance s WHERE s.id = " . $emp_array[0] . "";

$result = mysqli_query($connection, $query);

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

	$date_added = $row["date_added"];


}
$month = date("F", strtotime($date_added));
$year = date('Y', strtotime($date_added));
$date = date('d F, Y (l)', strtotime($date_added));
$sum = 0;
//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm



	$pdf->AddPage('L');

//set font to arial, bold, 14pt
	$pdf->SetFont('Arial', '', 14);

//Cell(width , height , text , border , end line , [align] )
	$width = array(10,15,20,25,30,35,40,45,50,55,60);
	$pdf->Image('../../../symfony/web/themes/default/images/MonetDT.png', 5, 5, 45, 15);
	$pdf->SetXY('65','5');
	$pdf->Cell('0','5','Monet Private Limited','','1','L');
	$pdf->SetX('65');
	$pdf->Cell('0','5','Employee Payroll Sheet','','1','L');
	$pdf->SetX('65');
	$pdf->Cell('0','5','For The Month Of '.$month.' '.$year.'','','1','L');

	$pdf->SetFont('Arial', '', 5);
	$pdf->SetFillColor('26','146','208');
	$pdf->SetTextColor('255','255','255');
	$pdf->Ln();
	$pdf->SetX(5);
$pdf->Cell(180,'3','','1','0','C','1');
$pdf->SetLeftMargin(186);
$pdf->Cell(60,'3','DEDUCTIONS','1','0','C','1');
$pdf->SetLeftMargin(247);
$pdf->Cell(15,'3','','1','0','C','1');
$pdf->SetLeftMargin(263);
$pdf->Cell(30,'3','','1','1','C','1');
$pdf->SetX(5);
$pdf->CellFitScale($width[0],'3','S.NO','1','0','C','1');
	$pdf->CellFitScale($width[2],'3','EMP #','1','0','C','1');
	$pdf->CellFitScale($width[3],'3','NAME','1','0','C','1');
	$pdf->CellFitScale($width[5],'3','DESIGNATION','1','0','C','1');


if (sizeof($allowance_real) > 0) {

	foreach ($allowance_real as $allowance_column) {
		$pdf->CellFitScale($width[1],'3',''.$allowance_column.'','1','0','C','1');

	}
}
$pdf->CellFitScale($width[1], '3', 'OTHER', '1', '0', 'C', '1');

$pdf->SetLeftMargin(186);

if (sizeof($deduction_real) > 0) {

	foreach ($deduction_real as $deduction_column) {
		$pdf->CellFitScale($width[1],'3',''.$deduction_column.'','1','0','C','1');

	}
}
$pdf->SetLeftMargin(247);


$pdf->CellFitScale($width[1],'3','NET SALARY','1','0','C','1');
$pdf->SetLeftMargin(263);


$pdf->CellFitScale($width[4],'3','ACCOUNT #','1','1','C','1');

// Data
$transport = 0;
$sno = 1;
$end = end($emp_array);
${'salary_allowance_sum'} =array();
${'salary_deduction_sum'} =array();
${'net_payment_array'} = array();

foreach ($emp_array as $emp) {
	if ($sno % 2 == 1) {
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0, 0, 0);
	} else {
		$pdf->SetFillColor(220, 220, 220);
		$pdf->SetTextColor(0, 0, 0);
	}
	$employee_name = 0;
	${'deduction' . $emp} = array();
	${'other_allowance_amount' . $emp} = array();
	${'other_allowance' . $emp} = array();
	${'salary_deduction' . $emp} = array();
	${'other_allowance_amount_sum' . $emp} = array();
	$query = "SELECT s.status,e.emp_firstname,e.emp_lastname,e.account_number,e.emp_other_name,e.custom1 as department,e.custom2 as designation,e.emp_other_amount,s.employee_id,s.basic,s.transport,s.house_rent,s.utilities,s.medical,s.date_added FROM hs_hr_emp_salarybreak_allowance s LEFT JOIN hs_hr_employee e ON s.employee_id = e.emp_number WHERE s.id = " . $emp . "";
	$result = mysqli_query($connection, $query);
	$grand_total = 0;
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$employee_name = $row["emp_firstname"] . ' ' . $row["emp_lastname"];
		$status = $row["status"];
		$basic = $row["basic"];
		$employee_id = $row['employee_id'];
		$house_rent = $row["house_rent"];
		$transport = $row['transport'];
		$utilities = $row["utilities"];
		$medical = $row["medical"];
		$department = $row['department'];
		$designation = $row['designation'];
		$medical = $row["medical"];
		if ($row["account_number"] != null) {
			$account = $row["account_number"];
		} else {
			$account = 'CHEQUE';

		}

		if ($row['emp_other_name'] != "" && $row['emp_other_amount']) {
			${'other_allowance' . $emp} = explode(',', $row['emp_other_name']);
			${'other_allowance_amount' . $emp} = explode(',', $row['emp_other_amount']);
			${'other_allowance_amount_sum' . $emp}[] = array_sum(${'other_allowance_amount' . $emp});
		}

		${'grand_total' . $emp} = $basic + $transport + $utilities + $medical + $house_rent;
	}


	$query3 = "SELECT allowance_id,deduction_id FROM hs_hr_emp_salarybreak_allowance WHERE id = " . $emp . " ";
	$result3 = mysqli_query($connection, $query3);
	while ($row3 = mysqli_fetch_assoc($result3)) {

		${'allowance_id' . $emp} = $row3["allowance_id"];
		${'deduction_id' . $emp} = $row3["deduction_id"];
		${'allowance_id' . $emp} = explode(",", ${'allowance_id' . $emp});

	}
	for ($i = 0; $i < sizeof(${'allowance_id' . $emp}); $i++) {
		$query4 = "SELECT a.allowance FROM hs_hr_emp_allowances a,hs_hr_emp_salarybreak_allowance s WHERE s.id =" . $emp . " AND a.id = (CAST(" . ${'allowance_id' . $emp}[$i] . " AS INT))";
		$result4 = mysqli_query($connection, $query4);
		$row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
		${'allowances' . $emp}[] = ($row4['allowance']);

	}

	$columns = mysqli_query($connection, "show columns from hs_hr_emp_salarybreak_allowance");
	while ($c = mysqli_fetch_assoc($columns)) {
		${'feild' . $emp}[] = $c['Field'];
	}

	$deduction_columns = mysqli_query($connection, "show columns from hs_hr_emp_salarybreak_deduction");
	while ($c = mysqli_fetch_assoc($deduction_columns)) {
		$deduction_feild[] = $c['Field'];
	}
	if (${'deduction_id' . $emp} != 0) {
		for ($k = 0; $k < sizeof($deduction_real); $k++) {
			$deduction_exists = false;
			$column = strtolower(str_replace(' ', '_', $deduction_real[$k]));
			if ($deduction_feild[$k + 1] == '' . $column . '') {
				$deduction_exists = true;
			}
			if ($deduction_exists) {
				$query6 = "SELECT $column FROM hs_hr_emp_salarybreak_deduction WHERE id =" . ${'deduction_id' . $emp} . " ";
				$result6 = mysqli_query($connection, $query6) or die('could not fetch: ' . mysqli_error($connection));
				$row6 = mysqli_fetch_assoc($result6);
				if ($row6[$column] != null) {
					${'salary_deduction' . $emp}[] = $row6[$column];
				}
			}

		}
	}

	for ($i = 0; $i < sizeof(${'allowance_id' . $emp}); $i++) {
		$exists = false;
		$keys = (array_keys(${'allowances' . $emp}));
		${'column' . $emp} = strtolower(str_replace(' ', '_', ${'allowances' . $emp}[$i]));

		for ($j = 0; $j < sizeof(${'feild' . $emp}); $j++) {

			if (${'feild' . $emp}[$j] == ${'column' . $emp}) {
				$exists = true;
				${'keys_reverse' . $emp}[] = ucfirst(str_replace('_', ' ', ${'allowances' . $emp}[$i]));
				$query5 = "SELECT ${'column'.$emp} FROM hs_hr_emp_salarybreak_allowance s WHERE s.id =" . $emp . " ";
				$result5 = mysqli_query($connection, $query5);
				$row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC);
				${'salary_allowance' . $emp}[] = ($row5[${'feild' . $emp}[$j]]);
			}
		}

	}
	for ($i = 0; $i < sizeof(${'allowance_id' . $emp}); $i++) {
		${'salary_allowance_sum' . $i}[] = (${'salary_allowance' . $emp}[$i]);
	}

	for ($i = 0; $i < sizeof(${'salary_deduction' . $emp}); $i++) {
		${'salary_deduction_sum' . $i}[] = (${'salary_deduction' . $emp}[$i]);
	}
//		${'salary_allowance_sum'}[] = (${'salary_allowance' . $emp}[0]);
//		${'salary_allowance_sum1'}[] = (${'salary_allowance' . $emp}[1]);
//		${'salary_allowance_sum2'}[] = (${'salary_allowance' . $emp}[2]);
//		${'salary_allowance_sum3'}[] = (${'salary_allowance' . $emp}[3]);


//	print_r(array_sum(${'salary_allowance_sum1'}));
//	print_r(array_sum(${'salary_allowance_sum2'}));
//	print_r(array_sum(${'salary_allowance_sum3'}));


	$allowance_real = ${'keys_reverse' . $emp};
	if (sizeof(${'other_allowance' . $emp}) > 0) {

		for ($i = 0; $i < sizeof(${'other_allowance_amount' . $emp}); $i++) {
			${'grand_total' . $emp} += ${'other_allowance_amount' . $emp}[$i];
		}
	}

	${'net_payment' . $emp} = ${'grand_total' . $emp};
	if (sizeof(${'salary_deduction' . $emp}) > 0) {
		for ($h = 0; $h < sizeof(${'salary_deduction' . $emp}); $h++) {
			${'net_payment' . $emp} -= ${'salary_deduction' . $emp}[$h];

		}
		${'net_payment_array'}[] = ${'net_payment' . $emp};
	}
	$pdf->SetX(5);

	$pdf->CellFitScale($width[0], '3', '' . $sno . '', '1', '0', 'C', '1');
	$pdf->CellFitScale($width[2], '3', 'MDT-19-' . $employee_id . '', '1', '0', 'C', '1');
	$pdf->CellFitScale($width[3], '3', '' . $employee_name . '', '1', '0', 'C', '1');
	$pdf->Cell($width[5], '3', '' . $designation . '', '1', '0', 'C', '1');


	if (sizeof(${'allowances' . $emp}) > 0) {
		$k = 0;
		foreach (${'allowances' . $emp} as $allo) {
			$pdf->CellFitScale($width[1], '3', 'RS/- ' . CurrencyFormat(${'salary_allowance' . $emp}[$k]) . '', '1', '0', 'C', '1');

			$k++;
		}

	}

	if (sizeof(${'other_allowance_amount_sum' . $emp}) > 0) {
		for ($j = 0; $j < sizeof(${'other_allowance_amount_sum' . $emp}); $j++) {

			$pdf->CellFitScale($width[1], '3', 'RS/- ' . CurrencyFormat(${'other_allowance_amount_sum' . $emp}[$j]) . '', '1', '0', 'C', '1');
			${'salary_other_sum'}[]  = ${'other_allowance_amount_sum' . $emp}[$j];
		}
	} else {
		$pdf->CellFitScale($width[1], '3', '0', '1', '0', 'C', '1');

	}

	$pdf->SetLeftMargin(186);

	if (sizeof(${'salary_deduction' . $emp}) > 0) {

		for ($j = 0; $j < sizeof(${'salary_deduction' . $emp}); $j++) {
			if (${'salary_deduction' . $emp}[$j] > 0) {
				$pdf->CellFitScale($width[1], '3', 'RS/- ' . CurrencyFormat(${'salary_deduction' . $emp}[$j]) . '', '1', '0', 'C', '1');
			} else {
				$pdf->CellFitScale($width[1], '3', '0', '1', '0', 'C', '1');

			}
		}
	}

	$pdf->SetLeftMargin(247);


	$pdf->CellFitScale($width[1], '3', 'RS/-' . CurrencyFormat(${'net_payment' . $emp}) . '', '1', '0', 'C', '1');

	$pdf->SetLeftMargin(263);


	$pdf->CellFitScale($width[4], '3', '' . $account . '', '1', '1', 'C', '1');
	$pdf->SetX(95);

	if ($emp == $end) {
		$pdf->SetX(5);

		$pdf->CellFitScale(90, '3', 'Total: ', '1', '0', 'C', '1');

		for ($i = 0; $i < sizeof(${'allowance_id' . $emp}); $i++) {
			$pdf->CellFitScale($width[1], '3', 'RS/- ' . CurrencyFormat(array_sum(${'salary_allowance_sum' . $i})) . '', '1', '0', 'C', '1');

		}
		$pdf->CellFitScale($width[1], '3', 'RS/- ' . CurrencyFormat(array_sum(${'salary_other_sum'})) . '', '1', '0', 'C', '1');
		$pdf->SetLeftMargin(186);

		for ($i = 0; $i < sizeof(${'salary_deduction' . $emp}); $i++) {
			$pdf->CellFitScale($width[1], '3', 'RS/- ' . CurrencyFormat(array_sum(${'salary_deduction_sum' . $i})) . '', '1', '0', 'C', '1');

		}
		$pdf->SetLeftMargin(247);

		$pdf->CellFitScale($width[1], '3', 'RS/-' . CurrencyFormat(array_sum(${'net_payment_array'})) . '', '1', '1', 'C', '1');

	}

	$sno++;
}

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetX(95);

$pdf->CellFitScale($width[2], '3', 'Transfered to BAFL ', '1', '0', 'C', '1');
$pdf->CellFitScale($width[2], '3', 'test', '1', '1', 'C', '1');
$pdf->SetX(95);
$pdf->CellFitScale($width[2], '3', 'Cheque', '1', '0', 'C', '1');
$pdf->CellFitScale($width[2], '3', 'Cheque', '1', '1', 'C', '1');
$pdf->SetX(95);
$pdf->CellFitScale($width[2], '3', 'Total Net Salary', '1', '0', 'C', '1');
$pdf->CellFitScale($width[2], '3', 'Cheque', '1', '1', 'C', '1');
$pdf->Line(150, 58, 170, 58); // 20mm from each edge
$pdf->SetXY(150,58);
$pdf->CellFitScale($width[2], '3', 'Prepared By', '0', '1', 'C', '0');
$pdf->Line(150, 61, 170, 61); // 20mm from each edge

$pdf->Line(190, 58, 210, 58); // 20mm from each edge
$pdf->SetXY(190,58);
$pdf->CellFitScale($width[2], '3', 'Approved By', '0', '1', 'C', '0');
$pdf->Line(190, 61, 210, 61); // 20mm from each edge
$pdf->Output();


?>

