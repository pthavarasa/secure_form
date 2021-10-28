<?php
  session_start();
  if (!isset($_SESSION['verified'])) {
    header('Location: login.php');
    exit;
  }
  if(isset($_POST['logout'])){
    session_destroy();
    header('Location: login.php');
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <style>
      h1, form {
        text-align: center;
        vertical-align: middle;
        line-height: 90px
      }
    </style>
  </head>
  <body>

    <h1>Welcome <?php echo explode('@', $_SESSION['email'])[0] ?></h1>
    <form action="" method="POST">
      <input type="submit" name="logout" value="Logout" />
    </form>

  </body>
</html>
