<?php

require_once('classes/database.php');
session_start();
$con = new database();

$sweetAlertConfig = "";
if(!isset($_POST['course_id']) || empty($_POST['course_id'])){
    header("Location: index.php");
    exit();

}

$course_id = $_POST['course_id'];
$course_data = $con-> getCourseById($course_id);

if(isset($_POST['save'])){
    
    $course_name = $_POST['course_name'];
    $course_id = $_POST['course_id'];

    $userID = $con->updateCourse($course_name, $course_id);

    if ($userID) {

        $sweetAlertConfig = "
         <script>
            Swal.fire({
             icon: 'success',
             title: 'Updated Successfully',
             text: 'You have successfully updated the course info.',
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
        <label for="course_id" class="form-label">Course ID</label>
        <input type="text" name="c_id" value="<?php echo $course_data['course_id'] ?>" id="course_id"  class="form-control" disabled required>
      </div>
      <div class="mb-3">
        <label for="course_name" class="form-label">Course</label>
        <input type="text" name="course_name" value="<?php echo $course_data['course_name'] ?>"  id="course_name" class="form-control" placeholder="Enter your new course name" required>
      </div>
      
      <input type="hidden" name="course_id" value="<?php echo $course_data['course_id'] ?>">
      <button id="saveButton" type="submit" name="save" class="btn btn-primary w-100">Save</button>
 
  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
  <script src="./package/dist/sweetalert2.js"></script>
  <?php echo $sweetAlertConfig; ?>
 
    </form>
  </div>
 
 
</body>
</html>