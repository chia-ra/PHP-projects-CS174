<?php


    // this code is used in conjunction with uploadFiles.php to create the MYSQL database

    $hn = 'localhost';
    $un = 'root';
    $pw = '';
    $db = 'testdb';
    $table = 'filedata';

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
                "Name VARCHAR(20) NOT NULL, " .
                "Content VARCHAR(200) NOT NULL)";
        $conn->query($query);
        if ($conn->error) die(mysql_error());
        $conn->close();
    }

?>
