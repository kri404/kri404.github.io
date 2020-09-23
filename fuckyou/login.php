<?php
/* start session */

// check if email and pass sent over
if(isset($_POST['email']) AND isset($_POST['passwd'])){

  // check if for sent over empty
  if(!empty($_POST['email']) AND !empty($_POST['passwd'])){

    // as vars
    $email = $_POST['email'];
    $passw = $_POST['passwd'];

    // validate email
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){

      // connection and connection check
      require("conf.php");
      $conn = new mysqli($sqlMain['host'], $sqlMain['user'], $sqlMain['pass'], $sqlMain['table']);
      if ($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
      }

      // sanitize
      $email = $conn -> real_escape_string($email);

      // get user with email
      $sql = "SELECT * FROM users WHERE email='$email'";
      $result = $conn->query($sql);
      $conn->close();

      // check if user with email exists
      if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        // check if password correct
        if(password_verify($passw, $user['pass_hash'])){

          // can start session
          $random = random_bytes(64);
          $session_hash = hash("sha256", $random);

          // session in db
          $conn = new mysqli($sqlMain['host'], $sqlMain['user'], $sqlMain['pass'], $sqlMain['table']);
          if ($conn -> connect_error) {
            die("Connection failed: " . $conn -> connect_error);
          }

          $expire = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +12 hours"));
          $sql = "INSERT INTO sessions (user_id, session_hash, expire) VALUES ('".$user['id']."', '".$session_hash."', '".$expire."')";
          $conn->query($sql);

          // in users browser
          setcookie("user", $session_hash, time()+43200);

          header("Location: user.php");

        }else{ // passwd incorrect
          header("Location: index.php?e=emailpass");
          exit();
        }

      }else{ // user with email does not exist
        header("Location: index.php?e=emailpass");
        exit();
      }

    }else{ // check if valid email format, can save from injection
      header("Location: index.php?e=emailpass");
      exit();
    }

  }else{ // email or password fiel empty
    header("Location: index.php?e=empty");
    exit();
  }

}else{ // nothing sent over
  header("Location: index.php?e=empty");
  exit();
}

?>
