<?php
// check if session is in dp, change valid 0
if(isset($_COOKIE['user'])){
  // sanitize
  $cookie = str_replace("/^[a-z0-9]/", "", $_COOKIE['user']);

  require("conf.php");
  $conn = new mysqli($sqlMain['host'], $sqlMain['user'], $sqlMain['pass'], $sqlMain['table']);
  if ($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
  }

  // sanitize
  $cookie = $conn -> real_escape_string($cookie);

  // get session as array
  $sql = "SELECT * FROM sessions WHERE session_hash='$cookie' ORDER BY id DESC LIMIT 1";
  $result = $conn -> query($sql);
  $session = $result -> fetch_assoc();

  // update session valid 0
  $sql = "UPDATE sessions SET valid='0' WHERE id='".$session['id']."'";
  $conn->query($sql);

  // remove from browser
  setcookie("user", "", time() - 3600);

  $conn -> close();
  header("Location: index.php?e=loggedout");

}
header("Location: index.php?e=loggedout");

?>
