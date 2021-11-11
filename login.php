<?php
  session_start();
  if (isset($_SESSION['verified'])) {
    header('Location: index.php');
    exit;
  }
  if(isset($_SESSION['token'])) $prev_token = $_SESSION['token'];
  $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

  if(isset($_POST['sign_in']) && isset($_POST['token']) && hash_equals($prev_token, $_POST['token'])){
    // check if data and not empty
    if (!isset($_POST['email']) || empty($_POST['email'])) $email_error = '- Invalide email!<br>';
    if (!isset($_POST['password']) || empty($_POST['password'])) $password_error = '- Invalide password!<br>';

    // sanitizing unsafe value
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])), ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])), ENT_QUOTES, 'UTF-8');

    // validation
    $email_pattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $password_pattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
    if(!preg_match($email_pattern, $email)) $email_error = '- Invalide email!<br>';
    if(!preg_match($password_pattern, $password)) $password_error = '- Invalide password, Require mixed password!<br>';

    if(!isset($email_error) && !isset($password_error)){
      // database connection
      $con = mysqli_connect('localhost', 'root', 'kjlJHKù!^^-*/565jg§jfgjf&', 'secure_form');
      if (mysqli_connect_errno()) {
        // It is recommended to avoid displaying error messages directly to the browser.
        //exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        echo "Failed to connect to MySQL";
        exit();
      }
      // check info
      if ($stmt = $con->prepare('SELECT user_email, user_password FROM credential WHERE user_email = ?')) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if(isset($result['user_email']) && $result['user_email'] === $email){
          if(isset($result['user_password']) && password_verify($password, $result['user_password'])){
            session_regenerate_id();
            $_SESSION['verified'] = TRUE;
            $_SESSION['email'] = $email;
            header('Location: index.php');
            exit;
          }else $credential_error = "- Email or password wrong!<br>";
        }else $credential_error = "- Email or password wrong!<br>";
      }else{
        // table not exist in database
        $database_error = "- Internal error contact admin!<br>";
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

    <title>Secure Login Form</title>
  </head>
  <body>
    <main class="form-signin">
      <form action="login.php" method="POST">
        <img class="mb-4" src="shield.png" alt="shield" width="100">
        <h1 class="h3">Login</h1>
        <?php if (isset($email_error) || isset($password_error) || isset($database_error) || isset($credential_error)): ?>
        <div class="alert alert-danger" role="alert">
          <?php if(isset($email_error)) echo $email_error; ?>
          <?php if(isset($password_error)) echo $password_error; ?>
          <?php if(isset($database_error)) echo $database_error; ?>
          <?php if(isset($credential_error)) echo $credential_error; ?>
        </div>
        <?php endif ?>
        <div class="form-floating">
          <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

        <button class="w-30 btn btn-lg btn-primary" type="reset">Reset</button>
        <button class="w-40 btn btn-lg btn-primary" name="sign_in" type="submit">Sign in</button>
        <a href="register.php" class="w-30 btn btn-lg btn-primary">Create</a>
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