<?php
  require 'functions.php';

  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
      //PRIKUPLJANJE UNESENIH PODATAKA KROZ REGISTRACIJU
      $username = addslashes($_POST['username']);
      $email = addslashes($_POST['email']);
      $password = addslashes($_POST['password']);
      $reg_date = date('Y-m-d H:i:s');

      //SMJESTANJE PRIKUPLJENIH PODATAKA U BAZU
      $query = "insert into users (username, email, password, reg_date) values 
      ('$username', '$email', '$password', '$reg_date')";
      $result = mysqli_query($conn, $query);

      //PREUSMJERAVANJE NA STRANICU ZA PRIJAVU
      header("Location: login.php");
      die;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registracija</title>
</head>
<body>
  <?php include 'header.php';?>
    <div class="sign-up">
      <h2>Registracija</h2>
        <form action="" method="post" style="margin: auto; padding:5px;">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
          <button> Registruj se</button>
        </form>
</div>
<?php include 'footer.php';?>
</body>
</html>