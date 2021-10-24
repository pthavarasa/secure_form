<?php

  if(isset($_POST['sign_up'])){
    // check if data and not empty
    if (!isset($_POST['email']) || empty($_POST['email'])) $email_error = 'Invalide email!';
    if (!isset($_POST['password']) || empty($_POST['password'])) $password_error = 'Invalide password!';
    if (!isset($_POST['repeat_password']) || empty($_POST['repeat_password'])) $password_error = 'Invalide password!';

    // sanitizing unsafe value
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $repeat_password = htmlspecialchars(stripslashes(trim($_POST['repeat_password'])));

    // validation
    $email_pattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $password_pattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
    if(!preg_match($email_pattern, $email)) $email_error = 'Invalide email!';
    if(!preg_match($password_pattern, $password)) $password_error = 'Invalide password!';
    if(!preg_match($password_pattern, $repeat_password)) $password_error = 'Invalide password!';

    // check if password is equal
    if(!($password === $repeat_password)) $password_match = 'Password not match!';

    if(!isset($email_error) && !isset($password_error) && !isset($password_match)){
      // database connection
      $con = mysqli_connect('localhost', 'root', '', 'secure_form');
      if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
      }
      // check if email exist
      if ($stmt = $con->prepare('SELECT user_email, user_password FROM credential WHERE user_email = ?')) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if(!isset($result['user_email'])){
          // store credential
          $stmt = $con->prepare('INSERT INTO credential (user_email, user_password) VALUES (?, ?)');
          // hash the password to not expose
          $encrypted_pwd = password_hash($password, PASSWORD_DEFAULT);
          $stmt->bind_param('ss', $email, $encrypted_pwd);
          $stmt->execute();
        }else
          $credential_error = "email is already exist!";
      }else{
        // table not exist in database
        $database_error = "internal error contact admin";
      }
    }else{
      // table not exist in database
      $database_error = "internal error contact admin";
    }
  }else{
    echo isset($email_error);
    echo isset($password_error);
    echo isset($password_match);
    echo isset($credential_error);
    echo isset($database_error);
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Bootstrap custom styles -->
    <link href="custom.css" rel="stylesheet">

    <title>Secure Register Form</title>
  </head>
  <body>
    <main class="form-signin">
      <form action="register.php" method="POST">
        <img class="mb-4" src="shield.png" alt="shield" width="100">
        <h1 class="h3">Register</h1>
        <div class="form-label-group">
          <input type="email" name="email" class="form-control" placeholder="Email address">
        </div>
        <div class="form-label-group">
          <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-label-group">
          <input type="password" name="repeat_password" class="form-control" placeholder="Repeat password">
        </div>

        <button class="w-30 btn btn-lg btn-primary" type="reset">Reset</button>
        <button class="w-40 btn btn-lg btn-primary" name="sign_up" type="submit">Sign up</button>
        <a href="index.php" class="w-30 btn btn-lg btn-primary">Login</a>
      </form>
    </main>

    <!-- custom script -->
    <script>
      // prevent form resubmission
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    </script>
    <script type="text/javascript" src="custom.js"></script>
  </body>
</html>