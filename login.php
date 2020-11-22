<?php

    // this code is used in conjunction with uploadSearch.php to create the MYSQL database

    $hn = 'localhost';
    $un = 'root';
    $pw = '';
    $db = 'test1db';
    $table = 'testtb';

    function create_database($hn, $un, $pw, $db, $table) {
        $conn = new mysqli($hn, $un, $pw);
        if ($conn->connect_error) die(mysql_error());

        $query = "CREATE DATABASE IF NOT EXISTS $db";
        $conn->query($query);
        if ($conn->error) die(mysql_error());

        $query = "USE $db";
        $conn->query($query);
        if ($conn->error) die(mysql_error());

        $query = "CREATE TABLE IF NOT EXISTS $table (" .
                "advisorName VARCHAR(64) NOT NULL, " .
                "studentName VARCHAR(64) NOT NULL, " .
                "studentID CHAR(9) NOT NULL, " .
                "classCode VARCHAR(15) NOT NULL)";
        $conn->query($query);
        if ($conn->error) die(mysql_error());
        $conn->close();
    }

?>
