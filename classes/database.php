<?php

class database{

    function opencon() {
        return new PDO(
            'mysql:host=localhost; dbname=dbs_app',
            username: 'root',
            password: ''
        );
    }

    function signupUser($firstname, $lastname, $username, $email, $password){
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare("INSERT INTO Admin (admin_FN, admin_LN, admin_username, admin_email, admin_password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $username, $email, $password]);

            //Get the newly inserted user_id
            $userID = $con->lastInsertID();
            $con->commit();

            //returns the new admin's ID so it can be used in other operations
            return $userID;
        }catch(PDOException $e){

            //reverts any chnages made during the transaction. This keeps the database clean and consistent in case of an error
            $con->rollBack();
            return false;
        }
    }

    function isUsernameExists($username) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM Admin WHERE admin_username = ?");
        $stmt->execute([$username]);

        // fetches the result of the sql query. fetchColumn() returns the first column of the first row-in this case, the number of matching records.
        $count = $stmt->fetchColumn();

        //returns true if one or more records were found(i.e., te username already exists)
        return $count > 0;
    }

    function isEmailExists($email){
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM Admin WHERE admin_email = ?");
        $stmt->execute([$email]);

        // fetches the result of the sql query. fetchColumn() returns the first column of the first row-in this case, the number of matching records.
        $count = $stmt->fetchColumn();

        //returns true if one or more records were found(i.e., the email already exists)
        return $count > 0;
    }

    function loginUser($username, $password){
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Admin WHERE admin_username = ?"); //? stands for placeholder
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['admin_password'])){

            return $user;
        }


    }
    function addStudent($firstname, $lastname, $email, $admin_id){
        $con = $this->opencon();

        try{
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO students (student_FN, student_LN, student_email, admin_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $email, $admin_id]);

            $userID = $con->lastInsertID();
            $con->commit();

            return $userID;
        }catch(PDOException $e){


            $con->rollBack();
            return false;


        }

    }

    function addCourses($course_name, $admin_id){
        $con = $this->opencon();

        try{
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO courses (course_name, admin_id) VALUES (?, ?)");
            $stmt->execute([$course_name, $admin_id]);

            $userID = $con->lastInsertID();
            $con->commit();

            return $userID;
        }catch(PDOException $e){


            $con->rollBack();
            return false;


        }

    }

    function isCourseExists($course_name){
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM courses WHERE course_name = ?");
        $stmt->execute([$course_name]);

        // fetches the result of the sql query. fetchColumn() returns the first column of the first row-in this case, the number of matching records.
        $count = $stmt->fetchColumn();

        //returns true if one or more records were found(i.e., the email already exists)
        return $count;
    }

    function getStudents(){
        $con = $this->opencon();

        return $con->query("SELECT * FROM students")->fetchAll();
    }

    function getStudentById($student_id){
        $con = $this->opencon();

        $stmt = $con->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateStudent($student_FN, $student_LN, $student_email, $student_id){
        try{

            $con = $this->opencon();
            $con->beginTransaction();

            $stmt = $con->prepare("UPDATE students SET student_FN=?, student_LN=?, student_email=? WHERE student_id=?");
            $stmt->execute([$student_FN, $student_LN, $student_email, $student_id]);

            $con->commit();
            return true;
        }catch(PDOException $e){
            $con->rollBack();
            return false;

        }
        
    }

    function getCourses(){
        $con = $this->opencon();

        return $con->query("SELECT * FROM courses")->fetchAll();
    }

    function getCourseById($course_id){
        $con = $this->opencon();

        $stmt = $con->prepare("SELECT * FROM courses WHERE course_id = ?");
        $stmt->execute([$course_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateCourse($course_name, $course_id){
        try{

            $con = $this->opencon();
            $con->beginTransaction();

            $stmt = $con->prepare("UPDATE courses SET course_name=? WHERE course_id=?");
            $stmt->execute([$course_name, $course_id]);

            $con->commit();
            return true;
        }catch(PDOException $e){
            $con->rollBack();
            return false;

        }
        
    }

}