<?php
  require 'functions.php';

  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
      $email = addslashes($_POST['email']);
      $password = addslashes($_POST['password']);

      //PROVJERA DA LI UNESENI EMAIL I SIFRA POSTOJE U BAZI
      $query = "select * from users where email = '$email' && password = '$password' limit 1";
      $result = mysqli_query($conn, $query);

      if(mysqli_num_rows($result) > 0){ //AKO JE BROJ REDOVA KOJI SE VRACA NAKON PRETRAGE BAZE VECI OD 0 - PRIJAVA USPJESNA
        $row = mysqli_fetch_assoc($result);
        $_SESSION['logged'] = $row;
        header("Location: profile.php");
        die;
      }
      else
      {
        $error = "Pogrešan e-mail ili sifra!"; //U SUPROTNOM, ISPISUJE SE GRESKA
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prijava</title>
</head>
<body>
<?php include 'header.php';?> 
<div class="login"> 
  <?php //ISPIS GRESKE
    if(!empty($error)){
      echo "<div style='margin:auto; text-align:center; background-color:#F15050; color:#000; font-size:20px; border-radius:15px;'>". $error . "</div>"; 
    }
  ?>
  <h2>Prijava</h2>
    <form method="post" style="margin:auto; padding:5px;">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button> Prijavi se</button>
    </form>
</div>
<?php include 'footer.php';?>
</body>
</html>

