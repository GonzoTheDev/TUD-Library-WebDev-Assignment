<?php

/* Database connection credentials */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'library');
 
/* Attempt to connect to MySQL database */
$db_con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check the connection
if($db_con === false){
    die("ERROR: Failed to connect. " . mysqli_connect_error());
}

?>