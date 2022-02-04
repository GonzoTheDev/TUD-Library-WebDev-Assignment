<?php

// PHP function to create a login alert message with Javascript
function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

// If user is logged in, do nothing, if not then alert a message and redirect to login page.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

    // Get the current page filename
    $pageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  

    echo '<div class="row">
                <div class="navcontainer"><font color="white">Hello, '. $_SESSION['fname'] . '! </font>&nbsp;&nbsp;
                    <div class="btn-group">'; 
                    if($pageName == "account.php") { echo '<a href="index.php" class="btn btn-dark">Library Search</a>'; } else { echo '<a href="account.php" class="btn btn-dark">My Account</a>'; }
    echo '<a href="Assets/PHP/logout.php" class="btn btn-secondary ml-2">Logout</a>
                    </div>
                </div>
            </div>';

} else {
    header("refresh: 0; url=login.php");
    phpAlert("You are not logged in, click ok to continue to login page..."); 
}

?>