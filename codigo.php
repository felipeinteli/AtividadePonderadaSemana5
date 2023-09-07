
<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the EMPLOYEES table exists. */
  VerifyEmployeesTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the EMPLOYEES table. */
  $employee_name = htmlentities($_POST['NAME']);
  $employee_address = htmlentities($_POST['ADDRESS']);

  if (strlen($employee_name) || strlen($employee_address)) {
    AddEmployee($connection, $employee_name, $employee_address);
  }
  
  /* Ensure that the STUDENTS table exists. */
  VerifyStudentsTable($connection, DB_DATABASE);

  $student_name = htmlentities($_POST['student_name']);
  $student_age = htmlentities($_POST['student_age']);
  $student_course = htmlentities($_POST['student_course']);
  $student_rate = htmlentities($_POST['student_rate']);

  if (strlen($student_name) || strlen($student_age) || strlen($student_course) || strlen($student_rate)) {
    AddStudent($connection, $student_name, $student_age, $student_course, $student_rate);
  }

?>

<h2>Employees Section</h2>

<!-- Input form for Employees-->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>ADDRESS</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="ADDRESS" maxlength="90" size="60" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<h2>Students Section</h2>

<!-- Input form for Students -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>Name</td>
      <td>Age</td>
      <td>Course</td>
      <td>Rate</td>
    </tr>
    <tr>
      <td><input type="text" name="student_name" maxlength="45" size="30" /></td>
      <td><input type="text" name="student_age" maxlength="3" size="5" /></td>
      <td><input type="text" name="student_course" maxlength="45" size="30" /></td>
      <td><input type="text" name="student_rate" maxlength="5" size="5" /></td>
      <td><input type="submit" value="Add Student" /></td>
    </tr>
  </table>
</form>

<!-- Display table data for Employees. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>ADDRESS</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Display table data for Students -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>Name</td>
    <td>Age</td>
    <td>Course</td>
    <td>Rate</td>
  </tr>

<?php

$result_students = mysqli_query($connection, "SELECT * FROM STUDENTS");

while($query_data_students = mysqli_fetch_row($result_students)) {
  echo "<tr>";
  echo "<td>",$query_data_students[0], "</td>",
       "<td>",$query_data_students[1], "</td>",
       "<td>",$query_data_students[2], "</td>",
       "<td>",$query_data_students[3], "</td>",
       "<td>",$query_data_students[4], "</td>";
  echo "</tr>";
}
?>

</table>




<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_free_result($result_students);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an employee to the table. */
function AddEmployee($connection, $name, $address) {
   $n = mysqli_real_escape_string($connection, $name);
   $a = mysqli_real_escape_string($connection, $address);

   $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS) VALUES ('$n', '$a');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyEmployeesTable($connection, $dbName) {
  if(!TableExists("EMPLOYEES", $connection, $dbName))
  {
     $query = "CREATE TABLE EMPLOYEES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90)
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}

function AddStudent($connection, $name, $age, $course, $rate) {
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $age);
    $c = mysqli_real_escape_string($connection, $course);
    $r = mysqli_real_escape_string($connection, $rate);
 
    $query = "INSERT INTO STUDENTS (NAME, AGE, COURSE, RATE) VALUES ('$n', '$a', '$c', '$r');";
 
    if(!mysqli_query($connection, $query)) echo("<p>Error adding student data.</p>");
 }
 
function VerifyStudentsTable($connection, $dbName) {
   if(!TableExists("STUDENTS", $connection, $dbName))
   {
      $query = "CREATE TABLE STUDENTS (
          ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          NAME VARCHAR(45),
          AGE INT(3),
          COURSE VARCHAR(45),
          RATE DECIMAL(5,2)
        )";
 
      if(!mysqli_query($connection, $query)) echo("<p>Error creating students table.</p>");
   }
 }

?>                        
                