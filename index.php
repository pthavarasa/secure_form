<?php 
  if(isset($_POST["create"])){
    echo "redirect to create account";
  }else if(isset($_POST["sign_in"]) && isset($_POST["email"]) && isset($_POST["password"])){
    echo "handle validation";
  }else{
    echo "handle invalid input!";
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