<?php
  // this code creates a secure user signup and login function as well as allows them
  // to upload .txt files to the database which will be displayed once the user logs in.
  // db_connect.php is used in conjunction to create the MYSQL database.
    require_once "db_connect.php";
    create_database($hn, $un, $pw, $db, $table1, $table2);
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die (mysql_error());

    function mysql_error() {
      echo "<br> Oops! Something went wrong. <br>";
    }


    start_html();

    login_event($conn, $table1, $table2);
    signup_event();
    signup_mysql($conn, $table1);
    //if (isset($_COOKIE["loginCookie"])) { upload_mysql($conn, $table2, $_COOKIE["loginCookie"]); }

    //upload_event($conn);

    function start_html() {
      //ob_end_clean();
      echo <<<_END
        <html><body>
        <h1> Login Portal </h1>
        <form method='post' enctype='multipart/form-data'>
        <p><input type='username' name='uLog' value='' placeholder='Username'></p>
        <p><input type='password' name='pLog' value='' placeholder='Password'></p>
        <p><input type='submit' name='login' value='Login'></p>
        </form>
        <form method='post'>
        <p><input type='submit' name='signup' value='Signup'></p>
        </form>
        <br>
      _END;
    }

    function login_event($conn, $table1, $table2) {
        if (isset($_POST['login'])) {
          if ((!$_POST['uLog']) || (!$_POST['pLog'])) {
            echo "All fields must have a valid entry to submit.";
            return;
          }
          else {
            $u= sanitizer($conn, $_POST["uLog"]);
            $p= sanitizer($conn, $_POST["pLog"]);

            $query = "SELECT * FROM $table1 WHERE username = '$u'";
            $result = $conn->query($query);
            if (!$result) die (mysql_error());
            $rows = $result->num_rows;
            if ($rows == 0) {
              echo "Invalid Username/Password combination. Please try again.";
            }
            else {
              $result->data_seek(0);
              $row = $result->fetch_array(MYSQLI_ASSOC);
              if (password_verify($p, $row['password'])) {
                echo "Login Successful.";
                upload_event($conn, $table2, $u);
                display_event($conn, $table2, $u);
              }
              else {
                echo "Invalid Username/Password combination. Please try again.";
              }

            }
          }
        }
    }

    function signup_event() {
        if (isset($_POST['signup'])) {
          echo <<<_END
            <h1>Signup Portal</h1>
            <form method='post' enctype='multipart/form-data'>
            <p><input type='email' name='eSign' value='' placeholder='Email id'></p>
            <p><input type="text" name="uSign" value='' placeholder='Username'></p>
            <p><input type="password" name="pSign" value='' placeholder="Password"></p>
            <p class="submit"><input type="submit" name='enroll' value="Signup"></p>
            </form>
          _END;

        }
    }
    function signup_mysql($conn, $table) {
      if (isset($_POST['enroll'])) {
        if ((!$_POST['eSign']) || (!$_POST['uSign']) || (!$_POST['pSign'])) {
          echo "All fields must have a valid entry to submit.";
          return;
        }
        else {
          $e= sanitizer($conn, $_POST["eSign"]);
          $u= sanitizer($conn, $_POST["uSign"]);
          $p= password_hash(sanitizer($conn, $_POST["pSign"]), PASSWORD_DEFAULT);

          $query = "SELECT * FROM $table WHERE username = '$u'";
          $result = $conn->query($query);
          if (!$result) die (mysql_error());

          $rows = $result->num_rows;
          if ($rows == 0) {
            $table_insert = $conn->prepare("INSERT INTO $table VALUES (?, ?, ?)");
            $table_insert->bind_param('sss', $e, $u, $p);
            $table_insert->execute();
            echo "Account created successfully.<br>";
            $table_insert->close();
          }
          else {
            echo "<br>Username not available. Please choose another.";
            return;
          }
        }
      }
    }

    function upload_event($conn, $table, $u) {
          echo <<<_END
            <h1>Upload Portal</h1>
            <p> Upload a .txt file </p>
            <form method='post' action="midterm2.php" enctype="multipart/form-data" >
            <p><input type="text" name="filename" value="" placeholder="File Name"></p>
            <p><input type="file" name='filepath' size='20'></p>
            <p><input type="submit" name='uploader' value="Upload"></p>
            </form>
          _END;
          //upload_mysql($conn, $table, $u);
          if ($_FILES) {
            if (!$_POST['filename']) {
              echo "All fields must have a valid entry to upload.";
              return;
            }
            $u= sanitizer($conn, $u);
            $f= sanitizer($conn, $_POST["filename"]);
            $p= sanitizer($conn, $_FILES['filepath']['name']);

            if($_FILES['filepath']['type'] == 'text/plain')
            {
              $fh = fopen("$p", 'r') or die ("Failed to open file.");
              $contents = file_get_contents("$p");
              fclose($fh);
              sanitizer($contents);

              $table_insert = $conn->prepare("INSERT INTO $table VALUES (?, ?, ?)");
              $table_insert->bind_param('sss', $u, $f, $contents);
              $table_insert->execute();
              echo "File uploaded successfully.<br>";
              $table_insert->close();
            }
            else
            {
              echo "File must be in .txt format. <br>";
            }
          }

    }

    function display_event($conn, $table, $u) {
      $u= sanitizer($conn, $u);

      $query = "SELECT * FROM $table WHERE user = '$u'";
      $result = $conn->query($query);
      if (!$result) die (mysql_error());
      $rows = $result->num_rows;
      if ($rows == 0) {
        echo "No files stored for this user.";
      }
      else {
        echo "<br> Existing User Files: <br>";
        for ($i = 0; $i < $rows; $i++) {
            $result->data_seek($i);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            echo "File Name: " . $row['fileName'] . "<br>";
            echo "File Content: " . $row['fileContent'] . "<br>";
            echo "<br>";
        }
    }
  }

    function sanitizer($conn, $string) {
        return htmlentities(sanitize($conn, $string));
    }
    function sanitize($conn, $string) {
        if(get_magic_quotes_gpc()) $string = stripslashes($string);
        return $conn->real_escape_string($string);
    }

?>
