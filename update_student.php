<?php

require_once('classes/database.php');
session_start();
$con = new database();

$sweetAlertConfig = "";
if(!isset($_POST['student_id']) || empty($_POST['student_id'])){
    header("Location: index.php");
    exit();

}

$student_id = $_POST['student_id'];
$student_data = $con-> getStudentById($student_id);

if(isset($_POST['save'])){
    
    $student_FN = $_POST['student_FN'];
    $student_LN = $_POST['student_LN'];
    $student_email = $_POST['student_email'];
    $student_id = $_POST['student_id'];

    $userID = $con->updateStudent($student_FN, $student_LN, $student_email, $student_id);

    if ($userID) {

        $sweetAlertConfig = "
         <script>
            Swal.fire({
             icon: 'success',
             title: 'Updated Successfully',
             text: 'You have successfully updated the student info.',
            confirmButtonText: 'OK'
          }).then(() => {
              window.location.href = 'index.php';
          });
         </script>";
    } else {
        $sweetAlertConfig = "
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Update Failed',
             text: 'An error occured during update. Please try again.'
         });
         </script>";
     }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center">Edit User</h2>
 
    <form method="POST" action="" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="student_id" class="form-label">Student ID</label>
        <input type="text" name="s_id" value="<?php echo $student_data['student_id'] ?>" id="student_id"  class="form-control" disabled required>
      </div>
      <div class="mb-3">
        <label for="student_FN" class="form-label">First Name</label>
        <input type="text" name="student_FN" value="<?php echo $student_data['student_FN'] ?>"  id="student_FN" class="form-control" placeholder="Enter your new first name" required>
      </div>
      <div class="mb-3">
        <label for="student_LN" class="form-label">Last Name</label>
        <input type="text" name="student_LN" value="<?php echo $student_data['student_LN'] ?>" id="student_LN" class="form-control" placeholder="Enter your new last name" required>
      </div>
      <div class="mb-3">
        <label for="student_email" class="form-label">Email</label>
        <input type="text" name="student_email" value="<?php echo $student_data['student_email'] ?>" id="student_email" class="form-control" placeholder="Enter your new email" required>
      </div>
      <input type="hidden" name="student_id" value="<?php echo $student_data['student_id'] ?>">
      <button id="saveButton" type="submit" name="save" class="btn btn-primary w-100">Save</button>
 
  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
  <script src="./package/dist/sweetalert2.js"></script>
  <?php echo $sweetAlertConfig; ?>
 
    </form>
  </div>
 
 
</body>
</html>