<?php
  // this code allows users to enter data into a MYSQL database and then
  // search the database for entries based on a specified parameter of the
  // existing entered data. This code works in conjunction with login.php
  // which creates the database.

    require_once "login.php";

    create_database($hn, $un, $pw, $db, $table);

    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die (mysql_error());

    function mysql_error()
    {
        echo "<br> Oops! Something went wrong. <br>";
    }

    // Add section and handler
    upload_html();
    upload_event($conn, $table);

    // Search section and handler
    search_html();
    search_event($conn, $table);

    $conn->close();

    function upload_html() {
        echo <<<_END
            <html><body>
            <h1> ADD </h1>
            <form method='post' action='uploadSearch.php' enctype='multipart/form-data'>
                Advisor Name: <input type='text' name='advisorName'><br>
                Student Name: <input type='text' name='studentName'><br>
                Student ID: <input type='text' name='studentID'><br>
                Class Code: <input type='text' name='classCode'><br>
                <input type='submit' name='upload' value='Submit'>
            </form>
            <br>
            _END;
    }

    function search_html() {
        echo <<<_END
            <br><br>
            <b> ---------------------------------------------------- </b>
            <h1> SEARCH </h1>
            <form method='post' action='uploadSearch.php' enctype='multipart/form-data'>
                Search by Advisor: <input type='text' name='searchName'>
                <br>
                <input type='submit' name='search' value='Search'>
            </form>
            <br><br>
            _END;
    }

    function upload_event($conn, $table) {
        if ($_POST['upload']) {
            // first check that there is a value in each field
            if (!$_POST['advisorName'] || !$_POST['studentName']
                    || !$_POST['studentID'] || !$_POST['classCode']) {
                echo "All fields must have a valid entry to submit.<br>";
                return;
            }
            // if all fields are filled, sanitize everything
            $advisorName = sanitizer($conn, $_POST['advisorName']);
            $studentName = sanitizer($conn, $_POST['studentName']);
            $studentID = sanitizer($conn, $_POST['studentID']);
            $classCode = sanitizer($conn, $_POST['classCode']);

            if(!ctype_digit($studentID) || strlen($studentID) != 9) {
                //since we are using CHAR(9) data type to store studentID, it should match the format.
                echo "Student ID format must be 9 digits only.<br>";
                return;
            }
            else {
                $table_insert = $conn->prepare("INSERT INTO $table VALUES (?, ?, ?, ?)");
                $table_insert->bind_param('ssss', $advisorName, $studentName, $studentID, $classCode);
                $table_insert->execute();
                echo "Data submitted successfully.<br>";
                $table_insert->close();
            }
        }
    }

    function search_event($conn, $table) {
        if ($_POST['search']) {
            if (!$_POST['searchName']) {
                echo "You must enter a name first to Search!";
                return;
            }

            $searchName = sanitizer($conn, $_POST['searchName']);

            $query = "SELECT * FROM $table WHERE advisorName = '$searchName'";
            $result = $conn->query($query);
            if (!$result) die (mysql_error());

            $rows = $result->num_rows;

            if ($rows == 0) {
                echo "No results found for this advisor.";
            }
            else {
                for ($i = 0; $i < $rows; $i++) {
                    $result->data_seek($i);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    //display the info of each matching row in the db for the advisor
                    echo "Advisor Name: " . $row['advisorName'] . "<br>";
                    echo "Student Name: " . $row['studentName'] . "<br>";
                    echo "Student ID: " . $row['studentID'] . "<br>";
                    echo "Class Code: " . $row['classCode'] . "<br>";
                    echo "<br>";
                }
            }
            $result->close();
        }
    }

    //sanitizer functions
    function sanitizer($conn, $string) {
        return htmlentities(sanitize($conn, $string));
    }
    function sanitize($conn, $string) {
        if(get_magic_quotes_gpc()) $string = stripslashes($string);
        return $conn->real_escape_string($string);
    }
?>
