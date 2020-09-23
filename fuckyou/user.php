<?php
// check if user cookie exists and is valid
if(isset($_COOKIE['user']) AND !empty($_COOKIE['user'])){

  // sanitize
  $cookie = str_replace("/^[a-z0-9]/", "", $_COOKIE['user']);

  // check lenght
  if(strlen($_COOKIE['user']) === 64){

    // connection and connection check
    require("conf.php");
    $conn = new mysqli($sqlMain['host'], $sqlMain['user'], $sqlMain['pass'], $sqlMain['table']);
    if ($conn -> connect_error) {
      die("Connection failed: " . $conn -> connect_error);
    }

    // sanitize
    $cookie = $conn -> real_escape_string($cookie);

    // get session as array
    $sql = "SELECT * FROM sessions WHERE session_hash='$cookie' LIMIT 1";
    $result = $conn->query($sql);
    $session = $result->fetch_assoc();

    // get user info as array
    $sql = "SELECT * FROM users WHERE id='".$session['user_id']."'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    // get user projects assoc array
    $sql = "SELECT * FROM user_project_assoc WHERE user_id='".$session['user_id']."'";
    $result = mysqli_fetch_all($conn -> query($sql), MYSQLI_ASSOC);

    $user_projects = array();
    foreach($result as $assoc){
      $sql = "SELECT * FROM projects WHERE id='".$assoc['project_id']."'";
      $result = $conn->query($sql);
      $aproject = $result->fetch_assoc();
      $user_projects[$aproject['id']] = $aproject;
    }

    $conn->close();
  }
}

if(isset($session) AND isset($user)){
  if(empty($user['image'])){
    $user['image'] = "img/noimage.png";
  }
?>
<!doctype html>
<html lang="en">
<head>
  <title>Welcome to re-work</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">

  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <link rel="stylesheet" type="text/css" href="css/user.css">
</head>
<body>
    <div class="center-login">
      <div class="wrapper">

        <img class="user-image" src="<?php echo $user['image']; ?>" alt="kri404 profile picture">
        <h3 class="welcome-user">Hi, <?php echo "{$user['name']} {$user['surname']}"; ?></h3>

        <div class="user-links">
          <ul>
            <li><a href="user-settings.php">My settings</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
        <?php
        if(!empty($user_projects)){
          foreach($user_projects as $project){
          ?>
          <div class="place-box">
            <iframe width="600" height="170" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $project['name'].$project['address'].$project['postcode']; ?>&output=embed"></iframe>

            <div class="project-info project-title"><?php echo $project['name']; ?></div>
            <div class="project-info project-address"><?php echo $project['address']." ".$project['postcode']; ?></div>
            <div class="project-info project-desc"><?php echo $project['description']; ?></div>

            <form method="POST" action="checkin.php" id="project-<?php echo $project['id']; ?>-checkin">
              <input name="project" type="hidden" value="<?php echo $project['id']; ?>">
            </form>
            <button form="project-<?php echo $project['id']; ?>-checkin" class="checkin a-button" onclick="return confirm('We will try to detect your location, if your location match required postcode (<?php echo $project['postcode']; ?>) you will be able to check in. Are you sure you want to continue?')">Check in to <?php echo $project['postcode']; ?></button>
          </div>
          <?php
          }
        }else{
        ?>
        <p class="text-error">
          You have not been added to any project, please contact your representative.
        </p>
        <?php
        }
        ?>
    </div>
  </div>
</body>
</html>

<?php
}else{
  header("Location: index.php?e=login");
}
?>
