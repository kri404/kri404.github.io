<!doctype html>
<html lang="en">
<head>
  <title>Welcome to re:work</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">

  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/login.css">

</head>
<body>
  <div class="center-login">
    <div class="wrapper">
      <?php
      require("e.php");
      ?>
      <form method="POST" id="user-login">
        <input name="email" class="login" type="txt" placeholder="your@email.com">
        <input name="passwd" class="login" type="password" placeholder="password">
      </form>
      <br>
      <button class="login a-button" formaction="login.php" form="user-login">Login</button>

    </div>
  </div>
</body>
</html>
