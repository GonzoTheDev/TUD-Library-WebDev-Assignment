<?php
// Initialize the session
session_start();
 
// If user is logged in, redirect to homepage.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Require database connection file
require_once "Assets/PHP/db_con.php";
 
// Initialize variables
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Form data processing after submit
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT ID, username, password, FirstName, Surname, AddressLine1, AddressLine2, City, Mobile, Telephone FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($db_con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $stored_password, $fname, $sname, $address1, $address2, $city, $mobile, $landline);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password == $stored_password){
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["fname"] = $fname;
                            $_SESSION["sname"] = $sname;
                            $_SESSION["address1"] = $address1;
                            $_SESSION["address2"] = $address2;
                            $_SESSION["city"] = $city;
                            $_SESSION["mobile"] = $mobile;
                            $_SESSION["landline"] = $landline;
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
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
<html>
<head>
    <title>Library | Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="Assets/Images/favicon.png">
    <style>
        body {
            background-color: #4db6ac !important;
        }
        .container{ 
            margin-top: 10vh;
            width: 400px; 
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
        <h2>Library | Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-dark" value="Login">
            </div>

            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>