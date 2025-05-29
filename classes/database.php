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
}