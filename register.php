<?php

// Require database connection file
require_once "Assets/PHP/db_con.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $fname = $sname = $address1 = $address2 = $city = $mobile = $landline = "";
$username_err = $password_err = $confirm_password_err = $mobile_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT ID FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($db_con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                    $fname = trim($_POST["fname"]);
                    $sname = trim($_POST["sname"]);
                    $address1 = trim($_POST["address1"]);
                    $address2 = trim($_POST["address2"]);
                    $city = trim($_POST["city"]);
                    $landline = trim($_POST["landline"]);
                }
            } else{
                echo "Oh no! Something has gone wrong! Please try again!";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Validate mobile phone number
    if(empty(trim($_POST["mobile"]))){
        $mobile_err = "Please enter a mobile number.";     
    } elseif(ceil(log10(trim($_POST["mobile"]))) < 10){
        $mobile_err = "Please enter a valid Irish mobile phone number (10-digit).";
    } else{
        $mobile = trim($_POST["mobile"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($mobile_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, FirstName, Surname, AddressLine1, AddressLine2, City, Mobile, Telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($db_con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssss", $param_username, $param_password, $param_fname, $param_sname, $param_address1, $param_address2, $param_city, $param_mobile, $param_landline);
            
            // Set parameters
            $param_username = $username;
            $param_password = $password;
            $param_fname = $fname;
            $param_sname = $sname;
            $param_address1 = $address1;
            $param_address2 = $address2;
            $param_city = $city;
            $param_mobile = $mobile;
            $param_landline = $landline;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($db_con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library | Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="Assets/Images/favicon.png">
    <style>
        body {
            background-color: #4db6ac !important;
        }
        .container{ 
            margin-top: 5vh;
            margin-bottom: 5vh;
            width: 600px; 
            padding: 25px; 
            background-color: #fff;
            border-radius: 25px;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }
        label, h2, h4 {
            color: #000;
        }
        a {
            color: #4db6ac;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Library | Registration</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <hr>
            <h4> Account Details </h4>
            <hr>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <hr>
            <h4> Contact Details </h4>
            <hr>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="fname" class="form-control">
            </div>  
            <div class="form-group">
                <label>Surname</label>
                <input type="text" name="sname" class="form-control">
            </div>  
            <div class="form-group">
                <label>Address Line 1</label>
                <input type="text" name="address1" class="form-control">
            </div>  
            <div class="form-group">
                <label>Address Line 2</label>
                <input type="text" name="address2" class="form-control">
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control">
            </div>
            <div class="form-group">
                <label>Mobile Phone</label>
                <input type="text" name="mobile" class="form-control <?php echo (!empty($mobile_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mobile; ?>">
                <span class="invalid-feedback"><?php echo $mobile_err; ?></span>
            </div>  
            <div class="form-group">
                <label>Landline Phone</label>
                <input type="text" name="landline" class="form-control">
            </div>  
            
            <div class="form-group">
                <input type="submit" class="btn btn-dark" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>