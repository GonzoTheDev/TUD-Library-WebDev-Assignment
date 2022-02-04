<?php 

    // Initialize the session
    session_start();
 
    // Require database connection file
    require("Assets/PHP/db_con.php");

    // Get current page number, if not set then set it to 1
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

    // Check if the user has entered either a book title, author or category to search
    if((isset($_GET['titleAuthor']) && (!empty($_GET['titleAuthor']))) || (isset($_GET['category'])) && (!empty($_GET['category']))) {

        // If the user searched by book title or author only
        if(isset($_GET['titleAuthor']) &&  (!empty($_GET['titleAuthor']))){

            // Set the search variable to the users input
            $search = trim($_GET['titleAuthor']);

            // Count the results from the database and set all pagination variables
            $result_count = mysqli_query($db_con, "SELECT COUNT(*) As total_records FROM books LEFT JOIN categories ON categories.CategoryID = books.Category WHERE BookTitle LIKE '%$search%' OR Author LIKE '%$search%'");
            $total_records = mysqli_fetch_array($result_count);
            $total_records = $total_records['total_records'];
            $total_no_of_pages = ceil($total_records / $records_per_page);
            $second_last = $total_no_of_pages - 1; 

            // Create the database query
            $sql_search = "SELECT * FROM books LEFT JOIN categories ON categories.CategoryID = books.Category WHERE BookTitle LIKE '%$search%' OR Author LIKE '%$search%' LIMIT $offset, $records_per_page";
        }

        // If the user searched by category only
        if(isset($_GET['category']) && (!empty($_GET['category']))){
            
            // Set the category variable to the users selection
            $category = trim($_GET['category']);

            // Count the results from the database and set all pagination variables
            $result_count = mysqli_query($db_con, "SELECT COUNT(*) As total_records FROM books LEFT JOIN categories ON categories.CategoryID = books.Category WHERE Category = '$category'");
            $total_records = mysqli_fetch_array($result_count);
            $total_records = $total_records['total_records'];
            $total_no_of_pages = ceil($total_records / $records_per_page);
            $second_last = $total_no_of_pages - 1; 

            // Create the database query
            $sql_search = "SELECT * FROM books LEFT JOIN categories ON categories.CategoryID = books.Category WHERE Category = '$category' LIMIT $offset, $records_per_page";
        }

    }else{

        ////////////////////////////
        // SHOW ALL BOOKS BY DEFAULT
        ////////////////////////////

        // Count all results from books
        $result_count = mysqli_query($db_con, "SELECT COUNT(*) As total_records FROM books");

        // Set pagination variables
        $total_records = mysqli_fetch_array($result_count);
        $total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $records_per_page);
        $second_last = $total_no_of_pages - 1; 

        // Create default database query
        $sql_search = "SELECT * FROM books LEFT JOIN categories ON categories.CategoryID = books.Category LIMIT $offset, $records_per_page";
    }

    // If bookisbn and username are set, reserve book
    if(isset($_GET['bookisbn']) && isset($_GET['username'])) {

        // Initialize and set the GET variables & current date variable
        $bookisbn = trim($_GET['bookisbn']);
        $username = trim($_GET['username']);
        $date = date('Y-m-d');

        // Check if book is available for reservation
        $sql_check = "SELECT * FROM books WHERE ISBN = '$bookisbn' AND Reserved = 'N'";
        $query_check = mysqli_query($db_con, $sql_check);

            // If the query returns a row, then the book is available.
            if(mysqli_num_rows($query_check) > 0) {

                // Create the reservation
                $sql_res = "INSERT INTO Reservations (ISBN, Username, ReserveDate) VALUES ('$bookisbn', '$username', '$date')";

                // If the reservation was created successfully, then update the book as unavailable
                if ($db_con->query($sql_res) === TRUE) {
                    $sql_update = "UPDATE books SET Reserved='Y' WHERE ISBN = '$bookisbn'";

                        // Set the alerts and their messages on success and failure
                        if ($db_con->query($sql_update) === TRUE) {
                            $reserve_success = '<div class="alert alert-success alert-dismissible fade show" role="alert">Book reserved successfully!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        } else {
                            $reserve_fail = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Book reservation failed, error updating books, please contact administrator.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                } else {
                    $reserve_fail = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Book reservation failed, error updating reservations, please contact administrator.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                }
            } else {
                $reserve_fail = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Book reservation failed, book is not available for reservation.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            }

    }
?>
<html>
    <head>

        <!-- Title -->
        <title>Library | Home</title>

        <!-- Stylesheets -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Assets/CSS/index.css">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="Assets/Images/favicon.png">

        <!-- Javascript -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                $('.alert').alert()
            })
        </script>
    </head>
    <body>
        
        <!-- Include the header -->
        <?php include("header.php"); ?>

        <div id="page-container">
            <div id="content-wrap">
                <?php if(isset($reserve_success)) { echo '<br><div class="container">' . $reserve_success . '</div>'; } if(isset($reserve_fail)) { echo '<br><div class="container">' . $reserve_fail . '</div>'; } ?> 
                <div class="container catalog <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){ } else { echo 'notloggedin'; } ?>">
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <h2>Search Library Catalog</h2>

                            <form class="" action="index.php" method="GET">
                                
                                <div class="form-group">
                                    <label for="titleAuthor">Book Title and/or Author: </label>
                                    <input type="text" class="form-control" name="titleAuthor">
                                </div>
                                <div class="form-group">
                                    <label for="category">Book Category</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value=""></option>
                                        <?php
                                            $sql = "SELECT * FROM categories";
                                            $query = mysqli_query($db_con, $sql);
                                            if(mysqli_num_rows($query) > 0) {
                                                while($row = mysqli_fetch_assoc($query)) {
                                                    echo '<option value="'.$row['CategoryID'].'">'.$row['CategoryID'].' - '.$row['CategoryDesc'].'</option>';
                                                }
                                            }

                                        ?>
                                    </select>
                                </div>
                            <button class="btn btn-dark" type="submit" value="submit">Submit</button>
                            <button class="btn btn-secondary ml-2" type="reset" value="Reset">Clear Form</button>
                            <a href="index.php"><button class="btn btn-secondary ml-2">Reset Filters</button></a>
                            <br>
                            </form>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>
                    <br><br><br>
                    <div class="row">
                        <h2 class="title">Results</h2>
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
                                <th scope="col">Reserve?</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if(isset($sql_search)){
                            $query = mysqli_query($db_con, $sql_search);

                            if(mysqli_num_rows($query) > 0) {
                                while($row = mysqli_fetch_assoc($query)) {
                                    echo '<tr>
                                            <td>' . $row['ISBN'] . '</td>
                                            <td>' . $row['BookTitle'] . '</td>
                                            <td>' . $row['Author'] . '</td>
                                            <td>' . $row['Edition'] . '</td>
                                            <td>' . $row['Year'] . '</td>
                                            <td>' . $row['CategoryDesc'] . '</td>';
                                            if($row['Reserved'] == 'Y'){
                                                echo '<td><button class="btn btn-danger" disabled>Unavailable</a></td>';
                                            }else{
                                            echo '<td><a class="btn btn-success" href="index.php?bookisbn=' . $row['ISBN'] . '&username='.$_SESSION['username'].'">Reserve</a></td>';
                                            }
                                        echo '</tr>
                                        ';
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
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

            <!-- Include the footer -->
            <?php include("footer.php"); ?>

        </div>
    </body>
</html>


