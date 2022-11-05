<?php
include_once 'Dbh.php';
session_start();

class UserAuth extends Dbh{
    private $db;

    public function __construct(){
        $this->db = new Dbh();
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        if($this->confirmPasswordMatch($password, $confirmPassword)){
            if($this->checkEmailExist($email)){
                echo "User Already Exits";
                return true;
            } else{
                $sql = "INSERT INTO Students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
                if($conn->query($sql)){
                    echo "<script>alert('User Registration Successful')</script>";
                    echo "<script>location.href='./forms/login.php'</script>";
                } else {
                    echo "Opps User Could not be Registerd ". $conn->error;
                }
            }
        }else{
            echo "<script>alert('Password Do Not Match')</script>";
            echo "<script>location.href='./forms/register.php'</script>";

        }

        
    }

    public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $user = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $email;
            $_SESSION["username"] = $user['full_names'];
            header("Location: ./dashboard.php");
        } else {
            header("Location: forms/login.php");
            header("Location: forms/login.php?failedLogin=failed");
        }
    }

    public function getUser($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);
        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['full_names'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px'> 
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id'" .
                     "value=" . $data['id'] . ">".
                    "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> </td>".
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }

    public function deleteUser($id){
        $conn = $this->db->connect();
        $sql = "DELETE FROM students WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            //header("refresh:0.5; url=action.php?all");
            echo "<script>alert('Deleted Successfully!')</script>";
            $this->getAllUsers();
        } else {
            //header("refresh:0.5; url=action.php?all=?message=Error");
            echo "<script>alert('An Error Occured!')</script>";
            $this->getAllUsers();
        }
    }

    public function updateUser($username, $password){
        $conn = $this->db->connect();
        if($this->checkEmailExist($username)){
            $sql = "UPDATE student SET password = '$password' WHERE email = '$username'";
            if($conn->query($sql)){
                header("Location: ./forms/login.php?update=success");
            } else {
                header("Location: forms/resetpassword.php?error=1");
            }
        }else{
            header("Location: forms/resetpassword.php?error=1");
        }
        
    }

    public function getUserByUsername($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function logout($username){
        session_start();
        session_destroy();
        header('Location: ./index.php');
    }

    public function confirmPasswordMatch($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    }

    /* public function validatePassword($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    } */

    public function checkEmailExist($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return true;
        } else {
            return false;
        }

    }
}