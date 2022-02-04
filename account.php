<?php 

    // Initialize the session
    session_start();
 

    // Include database connection file
    require("Assets/PHP/db_con.php");

    // Get current page number
    if (isset($_GET['page_no']) && $_GET['page_no']!="") {
        $page_no = $_GET['page_no'];
    } else {
    	$page_no = 1;
    }

    // Set pagination variables
    $records_per_page = 5;
    $offset = ($page_no-1) * $records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";

    // Count book reservations
    $user = $_SESSION['username'];
    $result_count = mysqli_query($db_con, "SELECT COUNT(*) As total_records FROM reservations WHERE Username = '$user'");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $records_per_page);
    if($total_no_of_pages == 0) {$total_no_of_pages = 1;}
    $second_last = $total_no_of_pages - 1; 

    // If bookisbn and username are set, cancel book reservation
    if(isset($_GET['bookisbn']) && isset($_GET['username'])) {
        $bookisbn = trim($_GET['bookisbn']);
        $username = trim($_GET['username']);

        // Check if book is set as reserved in the books table
        $sql_check = "SELECT * FROM books WHERE ISBN = '$bookisbn' AND Reserved = 'Y'";
        $query_check = mysqli_query($db_con, $sql_check);
            if(mysqli_num_rows($query_check) > 0) {
                $sql_res = "DELETE FROM Reservations WHERE ISBN = '$bookisbn' AND Username = '$username'";
                if ($db_con->query($sql_res) === TRUE) {
                    $sql_update = "UPDATE books SET Reserved='N' WHERE ISBN = '$bookisbn'";
                        if ($db_con->query($sql_update) === TRUE) {
                            $cancel_success = '<div class="alert alert-success alert-dismissible fade show" role="alert">Book reservation cancelled successfully!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        } else {
                            $cancel_fail = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Book reservation cancellation failed, error updating books, please contact administrator.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                } else {
                    $cancel_fail = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Book reservation cancellation failed, error updating reservations, please contact administrator.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                }
            } else {
                $cancel_fail = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Book reservation cancellation failed, book is not available for reservation.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            }

    }


?>
<html>
    <head>
        <title>Library | Account</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Assets/CSS/account.css">
        <link rel="icon" type="image/png" href="Assets/Images/favicon.png">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                $('.alert').alert()
            })
        </script>
    </head>
    <body>

        <?php include("header.php"); ?>

        <div id="page-container">
        <div id="content-wrap">
        <?php if(isset($cancel_success)) { echo '<br><div class="container">' . $cancel_success . '</div>'; } if(isset($cancel_fail)) { echo '<br><div class="container">' . $cancel_fail . '</div>'; } ?> 
        <br><br>
        <div class="container">
        <div class="row">
                <h2 class="title">Your Reservations</h2>
                <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
                    <strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
                </div>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ISBN</th>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col">Edition</th>
                        <th scope="col">Year</th>
                        <th scope="col">Category</th>
                        <th scope="col">Date of Reservation</th>
                        <th scope="col">Cancel Reservation</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $sql_res = "SELECT * FROM Reservations WHERE Username = '$user' LIMIT $offset, $records_per_page";
                $query = mysqli_query($db_con, $sql_res);
            
                                if(mysqli_num_rows($query) > 0) {
                                    while($row = mysqli_fetch_assoc($query)) {
                                        $isbn = $row['ISBN'];
                                        $date = $row['ReserveDate'];
                                        $sql_reservations = "SELECT * FROM books LEFT JOIN categories ON categories.CategoryID = books.Category WHERE ISBN = '$isbn'";
                                        $query2 = mysqli_query($db_con, $sql_reservations);

                                    if(mysqli_num_rows($query2) > 0) {
                                        while($row2 = mysqli_fetch_assoc($query2)) {
                                            echo '<tr>
                                                    <td>' . $row2['ISBN'] . '</td>
                                                    <td>' . $row2['BookTitle'] . '</td>
                                                    <td>' . $row2['Author'] . '</td>
                                                    <td>' . $row2['Edition'] . '</td>
                                                    <td>' . $row2['Year'] . '</td>
                                                    <td>' . $row2['CategoryDesc'] . '</td>
                                                    <td>' . $date . '</td>
                                                    <td><a class="btn btn-danger" href="account.php?bookisbn=' . $row2['ISBN'] . '&username='.$_SESSION['username'].'">Cancel</a></td>';
                                                    }
                                                echo '</tr>
                                                ';
                                        }
                                    }
                                }
                    
                ?>
                </tbody>
            </table>
            <?php
            if(!isset($sql_reservations)){
                    echo '<h3> There are no active reservations associated with this account.';
            }
            ?>
            <ul class="pagination">
                <?php if($page_no > 1){
                echo "<li><a class=\"btn btn-secondary ml-2\" href='?page_no=1'>First Page</a></li>";
                } ?>
                    
                <li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
                <?php if($page_no > 1){
                echo "<a class=\"btn btn-dark ml-2\" href='?page_no=$previous_page'>Previous</a>";
                } ?>
                </li>
                    
                <li <?php if($page_no >= $total_no_of_pages){
                echo "class='disabled'";
                } ?>>
                <?php if($page_no < $total_no_of_pages) {
                echo "<a class=\"btn btn-dark ml-2\" href='?page_no=$next_page'>Next</a>";
                } ?>
                </li>

                <?php if($page_no < $total_no_of_pages){
                echo "<li><a class=\"btn btn-secondary ml-2\" href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
                } ?>
            </ul>
            </div>
        </div>
            </div>
                <?php include("footer.php"); ?>
            </div>
    </body>
</html>