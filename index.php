<?php

  if(isset($_POST['create'])){
    echo 'redirect to create account';
  }else if(isset($_POST['sign_in'])){
    // check if data and not empty
    if (!isset($_POST['email']) || empty($_POST['email'])) $email_error = 'Invalide email!';
    if (!isset($_POST['password']) || empty($_POST['password'])) $password_error = 'Invalide password!';

    // sanitizing unsafe value
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));

    // validation
    $email_pattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $password_pattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
    if(!preg_match($email_pattern, $email)) $email_error = 'Invalide email!';
    if(!preg_match($password_pattern, $password)) $password_error = 'Invalide password!';

    if(!isset($email_error) && !isset($password_error)){
      // database connection
      $con = mysqli_connect('localhost', 'root', '', 'secure_form');
      if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
      }
      // check info
      if ($stmt = $con->prepare('SELECT user_email, user_password FROM credential WHERE user_email = ?')) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if(isset($result['user_email']) && $result['user_email'] === $email){
          echo "ok";
        }else
          $credential_error = "email or password wrong!";
      }else{
        // table not exist in database
        $database_error = "internal error contact admin";
      }
    }else{
      echo $email_error;
      echo $password_error;
    }
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

    <title>Secure Login Form</title>
  </head>
  <body>
    <main class="form-signin">
      <form action="index.php" method="POST">
        <img class="mb-4" src="shield.png" alt="shield" width="100">

        <div class="form-floating">
          <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>

        <button class="w-30 btn btn-lg btn-primary" type="reset">Reset</button>
        <button class="w-40 btn btn-lg btn-primary" name="sign_in" type="submit">Sign in</button>
        <button class="w-30 btn btn-lg btn-primary" name="create" type="submit">Create</button>
      </form>
    </main>

    <!-- custom script -->
    <script type="text/javascript" src="custom.js"></script>
  </body>
</html>