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


//get invoices data

$employee = ($_POST['employee_id']);

$emp_array = explode(',',$employee);
$pdf = new FPDF('P', 'mm', 'A4');
foreach ($emp_array as $emp) {
	$employee_name = 0;
	${'deduction'.$emp} = array();
	${'other_allowance_amount'.$emp} = array();
	${'other_allowance'.$emp} = array();
	${'salary_deduction'.$emp} = array();
	$query = "SELECT s.status,e.emp_firstname,e.emp_lastname,e.emp_other_name,e.custom1 as department,e.custom2 as designation,e.emp_other_amount,s.employee_id,s.basic,s.transport,s.house_rent,s.utilities,s.medical,s.date_added FROM hs_hr_emp_salarybreak_allowance s LEFT JOIN hs_hr_employee e ON s.employee_id = e.emp_number WHERE s.id = " . $emp . "";
	$result = mysqli_query($connection, $query);
	$grand_total = 0;
	$net_payment = 0;
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$employee_name = $row["emp_firstname"] . ' ' . $row["emp_lastname"];
		$status = $row["status"];
		$basic = $row["basic"];
		$employee_id = $row['employee_id'];
		$transport = $row["transport"];
		$house_rent = $row["house_rent"];
		$transport = $row["transport"];
		$utilities = $row["utilities"];
		$medical = $row["medical"];
		$designation = $row['department'];
		$department = $row['designation'];
		$medical = $row["medical"];
		$date_added = $row["date_added"];

		if ($row['emp_other_name'] != "" && $row['emp_other_amount']) {
			${'other_allowance'.$emp} = explode(',', $row['emp_other_name']);
			${'other_allowance_amount'.$emp} = explode(',', $row['emp_other_amount']);
		}

		${'grand_total'.$emp} = $basic + $transport + $utilities + $medical + $house_rent;
	}
	$month = date("F", strtotime($date_added));
	$year = date("Y", strtotime($date_added));
	$date = date('d F, Y (l)', strtotime($date_added));

	$query3 = "SELECT allowance_id,deduction_id FROM hs_hr_emp_salarybreak_allowance WHERE id = " . $emp . " ";
	$result3 = mysqli_query($connection, $query3);
	while ($row3 = mysqli_fetch_assoc($result3)) {

		${'allowance_id'.$emp} = $row3["allowance_id"];
		${'deduction_id'.$emp} = $row3["deduction_id"];
		${'allowance_id'.$emp} = explode(",", ${'allowance_id'.$emp});

	}
	$query7 = "SELECT deduction_name FROM hs_hr_emp_deductions WHERE id <> 0 AND status = 1 ";
	$result7 = mysqli_query($connection, $query7);
	while ($row7 = mysqli_fetch_assoc($result7)) {
		$deduction_real[] = $row7['deduction_name'];
	}
	for ($i = 0; $i < sizeof(${'allowance_id'.$emp}); $i++) {
		$query4 = "SELECT a.allowance FROM hs_hr_emp_allowances a,hs_hr_emp_salarybreak_allowance s WHERE s.id =" . $emp . " AND a.id = (CAST(" . ${'allowance_id'.$emp}[$i] . " AS INT))";
		$result4 = mysqli_query($connection, $query4);
		$row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC);
		${'allowances'.$emp}[] = ($row4['allowance']);
	}


	$columns = mysqli_query($connection, "show columns from hs_hr_emp_salarybreak_allowance");
	while ($c = mysqli_fetch_assoc($columns)) {
		${'feild'.$emp}[] = $c['Field'];
	}

	$deduction_columns = mysqli_query($connection, "show columns from hs_hr_emp_salarybreak_deduction");
	while ($c = mysqli_fetch_assoc($deduction_columns)) {
		$deduction_feild[] = $c['Field'];
	}
	if (${'deduction_id'.$emp} != 0) {
		for ($k = 0; $k < sizeof($deduction_real); $k++) {
			$deduction_exists = false;
			$column = strtolower(str_replace(' ', '_', $deduction_real[$k]));
			if ($deduction_feild[$k + 1] == '' . $column . '') {
				$deduction_exists = true;
			}
			if ($deduction_exists) {
				$query6 = "SELECT $column FROM hs_hr_emp_salarybreak_deduction WHERE id =" . ${'deduction_id'.$emp} . " ";
				$result6 = mysqli_query($connection, $query6) or die('could not fetch: ' . mysqli_error($connection));
				$row6 = mysqli_fetch_assoc($result6);
				if ($row6[$column] != null) {
					${'salary_deduction'.$emp}[] = $row6[$column];
				}
			}

		}
	}


	for ($i = 0; $i < sizeof(${'allowance_id'.$emp}); $i++) {
		$exists = false;
		$keys = (array_keys(${'allowances'.$emp}));
		${'column'.$emp} = strtolower(str_replace(' ', '_', ${'allowances'.$emp}[$i]));

		for ($j = 0; $j < sizeof(${'feild'.$emp}); $j++) {

			if (${'feild'.$emp}[$j] == ${'column'.$emp}) {
				$exists = true;
				$keys_reverse = ucfirst(str_replace('_', ' ', ${'allowances'.$emp}[$i]));
				$query5 = "SELECT ${'column'.$emp} FROM hs_hr_emp_salarybreak_allowance s WHERE s.id =" . $emp . " ";
				$result5 = mysqli_query($connection, $query5);
				$row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC);
				${'salary_allowance'.$emp}[] = ($row5[${'feild'.$emp}[$j]]);
			}
		}
	}
	if (sizeof(${'other_allowance'.$emp}) > 0) {

		for ($i = 0; $i < sizeof(${'other_allowance_amount'.$emp}); $i++) {
			${'grand_total'.$emp} += ${'other_allowance_amount'.$emp}[$i];
		}


	}
	${'net_payment'.$emp} = ${'grand_total'.$emp};
	if (sizeof(${'salary_deduction'.$emp}) > 0) {
		for ($h = 0; $h < sizeof(${'salary_deduction'.$emp}); $h++) {
			${'net_payment'.$emp} -= ${'salary_deduction'.$emp}[$h];
		}
	}


//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm



	$pdf->AddPage();

//set font to arial, bold, 14pt
	$pdf->SetFont('Arial', 'B', 14);

//Cell(width , height , text , border , end line , [align] )

	$pdf->Cell(130, 5, 'Monet DT', 0, 0);
	$pdf->Image('../../../symfony/web/themes/default/images/MonetDT.png', 125, 5, 70, 20);

//set font to arial, regular, 12pt
	$pdf->SetFont('Arial', '', 12);
	$pdf->Cell(59, 5, '', 0, 1);//end of line

	$pdf->Cell(130, 5, '[Street Address]', 0, 0);
	$pdf->Cell(59, 5, '', 0, 1);//end of line

//$pdf->Cell(34	,5,$invoice['date'],0,1);//end of line

	$pdf->Cell(130, 5, 'Phone [+12345678]', 0, 0);
	$pdf->Cell(59, 5, '', 0, 1);//end of line
	$pdf->Cell(130, 5, 'Email: hr@monet-online.net', 0, 0);//end of line


	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(189, 10, '', 0, 1);//end of line
	$pdf->SetFillColor('21', '146', '209');
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(189, 8, "Pay slip for the month of " . $month . " " . $year . "", 1, 1, 'C', 1);
//invoice contents

	$pdf->SetFont('Arial', '', 12);
	$pdf->SetTextColor(0, 0, 0);

	$pdf->Cell(50, 7, 'Employee Name: ', 0, 0);
	$pdf->Cell(60, 7, '' . strtoupper($employee_name) . '', 0, 1, 'L');

	$pdf->Cell(50, 7, 'Designation: ', 0, 0);
	$pdf->Cell(60, 7, '' . $designation . '', 0, 1, 'L');

	$pdf->Cell(50, 7, 'Department: ', 0, 0);
	$pdf->Cell(60, 7, '' . $department . '', 0, 1, 'L');

	$pdf->Cell(50, 7, 'Salary Month: ', 0, 0);
	$pdf->Cell(60, 7, '' . $month . '', 0, 1, 'L');

	$pdf->Cell(50, 7, 'Date: ', 0, 0);
	$pdf->Cell(60, 7, '' . $date . '', 0, 1, 'L');



	$pdf->SetFillColor('21', '146', '209');
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(69, 8, 'Particulars', '0', 0, 'C', 1);
	$pdf->Cell(60, 8, 'Advance', '0', 0, 'C', 1);
	$pdf->Cell(60, 8, 'Amount', '0', 0, 'C', 1);
	$pdf->Cell(189, 10, '', 0, 1);//end of line
	$pdf->SetTextColor(0, 0, 0);

	$k = 0;
	foreach (${'allowances'.$emp} as $allo) {
		$pdf->Cell(69, 7, '' . $allo . '', 0, 0);
		$pdf->Cell(60, 7, '0', 0, 0, 'R');
		$pdf->Cell(60, 7, 'RS/- ' . CurrencyFormat(${'salary_allowance'.$emp}[$k]) . '', 0, 1, 'R');
		$k++;
	}
	if (sizeof(${'other_allowance'.$emp}) > 0) {
		$pdf->SetFillColor('21', '146', '209');
		$pdf->SetTextColor(255, 255, 255);
		$pdf->Cell(189, 8, 'Other Allowances', '0', 1, 'C', 1);
		$pdf->SetFillColor('21', '146', '209');
		$pdf->SetTextColor(0, 0, 0);

		for ($j = 0; $j < sizeof(${'other_allowance'.$emp}); $j++) {

			$pdf->Cell(69, 7, '' . ucfirst(${'other_allowance'.$emp}[$j]) . '', 0, 0);
			$pdf->Cell(120, 7, 'RS/- ' . CurrencyFormat(${'other_allowance_amount'.$emp}[$j]) . '', 0, 1, 'R');

		}
	}


	$pdf->Cell(69);
	$pdf->SetFillColor('21', '146', '209');
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(60, 8, 'Sub Total', '0', 0, 'C', 1);
	$pdf->Cell(60, 8, 'RS/- ' . CurrencyFormat(${'grand_total'.$emp}) . '', '0', 1, 'C', 1);
	$pdf->Cell(189, 10, '', 0, 1);//end of line

	$pdf->SetFillColor('21', '146', '209');
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(189, 8, 'Deductions', '0', 1, 'C', 1);
	$pdf->SetFillColor('21', '146', '209');
	$pdf->SetTextColor(0, 0, 0);
	if (sizeof(${'salary_deduction'.$emp}) > 0) {

		for ($j = 0; $j < sizeof(${'salary_deduction'.$emp}); $j++) {
			if (${'salary_deduction'.$emp}[$j] > 0) {
				$pdf->Cell(69, 7, '' . $deduction_real[$j] . '', 0, 0);
				$pdf->Cell(120, 7, 'RS/- ' . CurrencyFormat(${'salary_deduction'.$emp}[$j]) . '', 0, 1, 'R');
			}

		}
	}

	$pdf->Cell(69);
	$pdf->SetFillColor('21', '146', '209');
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(60, 8, 'Net Salary', '0', 0, 'C', 1);
	$pdf->Cell(60, 8, 'RS/- ' . CurrencyFormat(${'net_payment'.$emp}) . '', '0', 1, 'C', 1);
	$pdf->SetFillColor('21', '146', '209');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(189, 10, '', 0, 1);//end of line

	$pdf->Cell(189, 10, '', 0, 1);//end of line

	$pdf->Cell(69, 7, 'This is Computer Generated Slip does not require Signature', 0, 1);

}

$pdf->Output();
?>
