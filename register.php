<?php
  session_start();
  if (isset($_SESSION['verified'])) {
    header('Location: index.php');
    exit;
  }
  if(isset($_SESSION['token'])) {
    $prev_token = $_SESSION['token'];
  }
  $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

  if(isset($_POST['sign_up']) && isset($_POST['token']) && hash_equals($prev_token, $_POST['token'])){
    // check if data and not empty
    if (!isset($_POST['email']) || empty($_POST['email'])) {
      $email_error = 'Invalide email!<br>';
    }
    if (!isset($_POST['password']) || empty($_POST['password'])) {
      $password_error = '4.Invalide password!<br>';
    }
    if (!isset($_POST['repeat_password']) || empty($_POST['repeat_password'])) {
      $password_error = '5.Invalide password!<br>';
    }
    // sanitizing unsafe value
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])), ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])), ENT_QUOTES, 'UTF-8');
    $repeat_password = htmlspecialchars(stripslashes(trim($_POST['repeat_password'])), ENT_QUOTES, 'UTF-8');

    // validation
    $email_pattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $password_pattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
    if(!preg_match($email_pattern, $email)) {
      $email_error = '1.Invalide email!<br>';
    }
    if(!preg_match($password_pattern, $password)) {
      $password_error = '2.Invalide password!<br>';
    }
    if(!preg_match($password_pattern, $repeat_password)) {
      $password_error = '3.Invalide password!<br>';
    }

    // check if password is equal
    if($password !== $repeat_password){
      $password_match = 'Password not match!';
    }

    if(!isset($email_error) && !isset($password_error) && !isset($password_match)){
      // database connection
      $con = mysqli_connect('localhost', $_SERVER['MYSQL_USER'], $_SERVER['MYSQL_PASSWORD'], $_SERVER['MYSQL_DB_NAME']);
      if (mysqli_connect_errno()) {
        // It is recommended to avoid displaying error messages directly to the browser.
        //exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        echo "Failed to connect to MySQL";
        exit();
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
          $succ = "account created.";
        }else
          {$credential_error = "email is already exist!<br>";}
      }else{
        // table not exist in database
        {$database_error = "internal error contact admin<br>";}
      }
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

    <title>Secure Register Form</title>
  </head>
  <body>
    <main class="form-signin">
      <form action="register.php" method="POST">
        <img class="mb-4" src="shield.png" alt="shield" width="100">
        <h1 class="h3">Register</h1>
        <?php if (isset($email_error) || isset($password_error) || isset($database_error) || isset($credential_error) || isset($password_match)): ?>
        <div class="alert alert-danger" role="alert">
          <?php if(isset($email_error)) {echo $email_error;} ?>
          <?php if(isset($password_error)) {echo $password_error;} ?>
          <?php if(isset($database_error)) {echo $database_error;} ?>
          <?php if(isset($credential_error)) {echo $credential_error;} ?>
          <?php if(isset($password_match)) {echo $password_match;} ?>
        </div>
        <?php endif ?>
        <?php if (isset($succ)): ?>
          <div class="alert alert-success" role="alert">Account created.</div>
        <?php endif ?>
        <div class="form-label-group">
          <input type="email" name="email" class="form-control" placeholder="Email address">
        </div>
        <div class="form-label-group">
          <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-label-group">
          <input type="password" name="repeat_password" class="form-control" placeholder="Repeat password">
        </div>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

        <button class="w-30 btn btn-lg btn-primary" type="reset">Reset</button>
        <button class="w-40 btn btn-lg btn-primary" name="sign_up" type="submit">Sign up</button>
        <a href="login.php" class="w-30 btn btn-lg btn-primary">Login</a>
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