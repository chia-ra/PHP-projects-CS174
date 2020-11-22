<?php

// this code allows a user to upload .txt files to a MYSQL database along with a
// specified name, and outputs all previously uploaded files to the webpage from the database
// to be used in conjunction wih login1.php which creates the database

require_once 'login1.php';
create_database($hn, $un, $pw, $db, $table);

echo <<<_END
    <html><head><title>PHP Upload</title></head><body>
    <form method='post' action='uploadFiles.php' enctype='multipart/form-data'><pre>
    Select a text file (*.txt): <input type='file' name='filename' size='10'>
    Name: <input type="text" name="fname">
    <input type='submit' value='Submit'>
    </pre></form>
    _END;

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die(mysql_error("Cannot connect! "));

function mysql_error($msg)
{
    echo "Oops! Something went wrong. <br><br>";
}

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

if($_FILES && isset($_POST['fname'])) {
    $fnameSanitized = htmlentities($_FILES['filename']['name']);

    if($_FILES['filename']['type'] == 'text/plain') {
      $ext = 'txt';
    }
    else {
      $ext = '';
    }

    if ($ext) {
        $data=file_get_contents($fnameSanitized);
        $name = $fnameSanitized;
        $fname = get_post($conn, 'fname');
        if (empty($fname)){
            $fname = $name;
        }
        echo "Uploaded file '$name' as '$fname':<br>";

        $data = preg_replace("/[\n\r]/","",$data);
        $query= "INSERT INTO filedata VALUES" . "('$fname','$data')";
        $result=$conn->query($query);
        if (!$result) echo "Oops! Something went wrong. <br><br>";
    }
    else echo "'$name' is not an accepted file";
}

$query = "SELECT * FROM filedata";
$result = $conn->query($query);
if (!$result) echo "Oops! Something went wrong. <br><br>";

$rows = $result->num_rows;
if($rows > 0){
    for ($j = 0 ; $j < $rows ; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        echo 'Name: ' . $row['Name'] . '<br>';
        echo 'Content: ' . $row['Content'] . '<br>';
    }
}

$result->close();
$conn->close();
echo "</body></html>";
?>
