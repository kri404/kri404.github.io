<?php
/* Errors */

if(isset($_GET['e'])){

  $e = $_GET['e'];
  // email and/or password field sent empty
  if($e === "empty"){
    echo "<div class='eerror'>Email and password required.</div>";
  }
  // wrong email or pass
  if($e === "emailpass"){
    echo "<div class='eerror'>Email or password incorrect.</div>";
  }
  // logged out
  if($e === "loggedout"){
    echo "<div class='eerror-green'>Done, see you later.</div>";
  }




}

?>
