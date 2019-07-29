<?php
define("DB_HOST", "localhost");
define("DB_NAME", "orangehrm");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

function connect(){

    $connection=mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
    if(!$connection){
        die('Could not connect database');
    }
    return $connection;
}

$conn = connect();
if(isset($_POST['salary_id'])) {
    $salary_id = trim( $_POST['salary_id'] );

        $sql = "DELETE hs_hr_emp_salarybreak_allowance,hs_hr_emp_salarybreak_deduction FROM hs_hr_emp_salarybreak_allowance INNER JOIN hs_hr_emp_salarybreak_deduction ON hs_hr_emp_salarybreak_allowance.deduction_id = hs_hr_emp_salarybreak_deduction.id WHERE hs_hr_emp_salarybreak_allowance.id in (".$salary_id.")";
        $result = mysqli_query( $conn, $sql ) or die( "database error:" . mysqli_error( $conn ) );
    if($result){
        echo $salary_id;
    }else{
        echo 'error';
    }

}
?>
