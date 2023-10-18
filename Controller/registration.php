<?php
// Include the config file with database info
require_once "config.php";
 
// Defines the variables and initialises with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$address = $_POST["address"];
$phone = $_POST["phone"];
$age = "";
$ageError = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate the username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // creates a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // connects variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set the parameters
            $param_username = trim($_POST["username"]);
            
            // Tries to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* stores the result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Error! Something went wrong. Please try again later.";
            }

            // Closes the statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validates the password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validates the confirmation password (second time entering password)
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Checks the input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Creates an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // connects variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set the parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Tries to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirects the user to login page
                header("location: login.php");
            } else{
                echo "Error! Something went wrong. Please try again later.";
            }

            // Ends the statement
            mysqli_stmt_close($stmt);
        }
    }

     # Validate Date of Birth
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the user's entered age
        $age = $_POST["age"];

        if (empty($age)) {
            $ageError = "Age is required.";
        } elseif (!is_numeric($age) || $age <= 0) {
            $ageError = "Please enter a number for age.";
        } elseif ($age < 18) {
            $ageError = "You must be at least 18 years old.";
        } else {
        }
    }
    // Ends the connection
    mysqli_close($link);
}
?>