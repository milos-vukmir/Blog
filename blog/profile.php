<?php 
require 'functions.php'; 
check_login();

//BRISANJE OBJAVE
if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'post_delete'){

  $id = $_GET['id'] ?? 0;
  $user_id = $_SESSION['logged']['id'];
  $query = "select * from posts where id = '$id' && user_id='$user_id' limit 1";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);

      if(file_exists($row['image'])){
        unlink($row['image']);
      }
  }

  $query = "delete from posts where id = '$id' && user_id='$user_id' limit 1";
  $result = mysqli_query($conn, $query);

  header("Location: profile.php");
  die;
}

//UREDJIVANJE OBJAVE
elseif($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'post_edit')
{
  $id = $_GET['id'] ?? 0;
  $user_id = $_SESSION['logged']['id'];

  $image_added = false;

  if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0){

      $folder = "uploads/"; 

        if(!file_exists($folder))
        {
            mkdir($folder, 0777, true);
        }

      $image = $folder . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], $image);
      
      $query = "select * from posts where id = '$id' && user_id='$user_id' limit 1";
      $result = mysqli_query($conn, $query);

      if(mysqli_num_rows($result) > 0){
          $row = mysqli_fetch_assoc($result);

            if(file_exists($row['image'])){
              unlink($row['image']);
      }
    }

      $image_added = true;
  } 

      $post = addslashes($_POST['post']);

      if($image_added == true){
          $query = "update posts set post = '$post', 
                  image = '$image'
                  where id = '$id' && user_id = '$user_id' limit 1";
      }
      else{
          $query = "update posts set post = '$post'
                  where id = '$id' && user_id = '$user_id' limit 1";
      }
      
      $result = mysqli_query($conn, $query);

      header("Location: profile.php");
      die;
}

//_______________________________________________________

//BRISANJE PROFILA
elseif($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'delete'){

    $id = $_SESSION['logged']['id'];
    $query = "delete from users where id = '$id' limit 1";
    $result = mysqli_query($conn, $query);

    //BRISANJE SLIKE KORISNIKA
    if(file_exists($_SESSION['logged']['image'])){
      unlink($_SESSION['logged']['image']);
    }

    //BRISANJE OBJAVE KORISNIKA
    $query = "delete from posts where user_id = '$id'";
    $result = mysqli_query($conn, $query);

    header("Location: logout.php");
    die;
}

//UREDJIVANJE PROFILA
elseif($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username']))
{
    $image_added = false;

    if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0){

        $folder = "uploads/"; //novi folder
          if(!file_exists($folder))
          {
              mkdir($folder, 0777, true);
          }

        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
        
        //BRISANJE STARE SLIKE NAKON PROMJENE
        if(file_exists($_SESSION['logged']['image'])){
          unlink($_SESSION['logged']['image']);
        }

        $image_added = true;
    } 

        $username = addslashes($_POST['username']);
        $email = addslashes($_POST['email']);
        $password = addslashes($_POST['password']);
        $id = $_SESSION['logged']['id'];

        //AZURIRANJE UNESENIH PODATAKA KROZ UREDJIVANJE PROFILA (SA SLIKOM)
        if($image_added == true){
            $query = "update users set username = '$username', 
                    email = '$email',
                    password = '$password',
                    image = '$image'
                    where id = '$id' limit 1";
        }
        //AZURIRANJE UNESENIH PODATAKA KROZ UREDJIVANJE PROFILA (BEZ SLIKE)
        else{ 
            $query = "update users set username = '$username', 
                    email = '$email',
                    password = '$password'
                    where id = '$id' limit 1";
        }

        $result = mysqli_query($conn, $query);

        //SELEKTOVANJE CIJELOG REDA SA ID-jem PRIJAVLJENOG KORISNIKA
        $query = "select * from users where id = '$id' limit 1";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0)
        {
            //DOHVATANJE REDA I POHRANJIVANJE PODATAKA VEZANIH ZA PRIJAVLJENOG KORISNIKA
            $_SESSION['logged'] = mysqli_fetch_assoc($result);
        }
        header("Location: profile.php");
        die;
  }

//DODAVANJE OBJAVE
elseif($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['post']))
{
    $image = "";
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0){

        $folder = "uploads/"; //NOVI FOLDER
          if(!file_exists($folder))
          {
              mkdir($folder, 0777, true);
          }

        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);        
    } 

        $post = addslashes($_POST['post']);
        $user_id = $_SESSION['logged']['id'];
        $date = date('Y-m-d H:i:s');
        //POHRANA UNESENE OBJAVE U BAZU
        $query = "insert into posts (user_id, post, image, date) 
                              values ('$user_id', '$post', '$image', '$date')";
        
        $result = mysqli_query($conn, $query);

        header("Location: profile.php");
        die;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
</head>
<body>
<?php include 'header.php';?>
<div class="profile-posts">

<!-- BRISANJE OBJAVE -->
<?php if(!empty($_GET['action']) && $_GET['action'] == 'post_delete' && !empty($_GET['id'])):?>

  <?php 
      $id = (int)$_GET['id'];
      $query = "select * from posts where id = '$id' limit 1";
      $result = mysqli_query($conn, $query);
    ?>

  <?php if(mysqli_num_rows($result) > 0):?>
    <?php $row = mysqli_fetch_assoc($result)?>

  <h3>Da li ste sigurni da želite obrisati objavu?</h3>

  <!--FORMA ZA BRISANJE OBJAVE-->
  <form method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">
      <img src="<?php echo $row['image']?>" style="width:100%; height:150px; object-fit:cover;">
      <div><?php echo $row['post']?></div>
      <input type="hidden" name="action" value="post_delete">
        <button>Obrisi</button>
        <a href="profile.php">
            <button type="button">Otkaži</button>
        </a> 
  </form> 
  <?php endif;?>

<!-- PRIKAZ STRANICE ZA UREDJIVANJE OBJAVE -->
<?php elseif(!empty($_GET['action']) && $_GET['action'] == 'post_edit' && !empty($_GET['id'])):?>

  <?php 
      $id = (int)$_GET['id'];
      $query = "select * from posts where id = '$id' limit 1";
      $result = mysqli_query($conn, $query);
    ?>

  <?php if(mysqli_num_rows($result) > 0):?>
    <?php $row = mysqli_fetch_assoc($result)?>

  <h5>Izmjeni objavu</h5>
  <!--FORMA ZA IZMJENU OBJAVE-->
  <form method="post" enctype="multipart/form-data" style="margin:auto; padding:10px;">
      <img src="<?php echo $row['image']?>" style="width:100%; height:150px; object-fit:cover;">
      Umetni sliku:<input type="file" name="image"> <br>
      <textarea name="post" rows="8"><?php echo $row['post']?></textarea>
      <input type="hidden" name="action" value="post_edit">
        <button> Sacuvaj</button>
        <a href="profile.php">
            <button type="button">Otkaži</button>
        </a>  
  </form> 
  <?php endif;?>

<!-- PRIKAZ STRANICE ZA UREDJIVANJE PROFILA -->
  <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'edit'):?>
    
    <h2 style="text-align:center;">Uredi profil</h2>

      <form action="" method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">
          <img src="<?php echo $_SESSION['logged']['image']; ?>" style="margin:auto; display:block; height:150px; width:150px; object-fit:cover;">
          Postavi sliku:

          <input type="file" name="image" ><br>
          <input type="text" name="username" placeholder="Username" value="<?php echo $_SESSION['logged']['username']; ?>"  required><br>
          <input type="email" name="email" placeholder="Email" value="<?php echo $_SESSION['logged']['email']; ?>" required><br>
          <input type="text" name="password" placeholder="Password" value="<?php echo $_SESSION['logged']['password']; ?>" required><br>
        
          <button>Sačuvaj</button>
          <a href="profile.php">
            <button type="button">Otkaži</button>
          </a> 
      </form>

<!-- PRIKAZ STRANICE ZA BRISANJE PROFILA-->
    <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'delete'):?> 
      
      <h2 style="text-align:center;">Da li ste sigurni da želite obrisati profil?</h2>
        
        <div style="margin:auto; max-width:600px; text-align:center;">     
          <form method="post" style="margin:auto; padding:10px;">
              <img src="<?php echo $_SESSION['logged']['image']; ?>" style="margin:auto; display:block; height:150px; width:150px; object-fit:cover;">
              <div> <?php echo $_SESSION['logged']['username'];?> </div>
              <div> <?php echo $_SESSION['logged']['email'];?> </div>
              <input type="hidden" name="action" value="delete">
            <button>Obriši</button>
            <a href="profile.php">
              <button type="button">Otkaži</button>
            </a>
          </form>
        </div>

<!-- PRIKAZ STRANICE PROFILA SA PROF. SLIKOM, USERNAME-OM I MEJLOM, KAO I DUGMADI UREDI I OBRISI PROFIL-->
  <?php else:?>
    <h2>Profil</h2><br>
      <div style="margin:auto; max-width:600px; text-align:center;">
        <div>
          <img src="<?php echo $_SESSION['logged']['image']; ?>" style="height:150px; width:150px; object-fit:cover;">
        </div>
        <div style="margin-bottom:5px;">
            <?php echo $_SESSION['logged']['username'];?> 
        </div>
        <div style="margin-bottom:20px;">
            <?php echo $_SESSION['logged']['email'];?>
        </div>
          <a href="profile.php?action=edit">
            <button>Uredi profil</button>
          </a>
          <a href="profile.php?action=delete">
            <button>Obriši profil</button>
          </a>
</div><br>
<hr>
<!-- FORMA ZA KREIRANJE OBJAVA -->
<h4>Kreiraj objavu</h4>
  <form method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">
      Umetni sliku:<input type="file" name="image"> <br>
      <textarea name="post" rows="8" placeholder="Ovdje upišite objavu...."></textarea>
      <button> Objavi</button>
  </form>
<hr>

<div>
<!-- ISPISIVANJE POSTOVA TRENUTNOG KORISNIKA -->
  <?php 
    $id = $_SESSION['logged']['id'];
    $query = "select * from posts where user_id = '$id' order by id desc";
    $result = mysqli_query($conn, $query);
  ?>
<!-- ISPISIVANJE POSTOVA -->
  <?php if(mysqli_num_rows($result) > 0):?>

    <?php while($row = mysqli_fetch_assoc($result)):?>

      <?php
        $user_id = $row['user_id'];
        $query = "select username, image from users where id = '$user_id' limit 1";
        $result2 = mysqli_query($conn, $query);

        $user_row = mysqli_fetch_assoc($result2);
      ?>
<!-- PRIKAZ POSTOVA -->
      <div style="display:flex; border: #323232 solid thin; margin: 10px 0px 10px 0px; padding:3px;">
<!-- PRIKAZ PROFILNE SLIKE I USERNAME KORISNIKA KOJI JE POSTAVIO OBJAVU (LIJEVA STRANA)-->
          <div style="flex: 1; text-align:center; padding-bottom:2px;">
             <img src="<?php echo $user_row['image']?>" style="margin:5px; width:100px; height:100px; object-fit:cover; border-radius:50px;"><br>               
              <?php echo $user_row['username']?>
          </div>
<!-- PRIKAZ SADRZAJA OBJAVE I SLIKE UKOLIKO POSTOJI--> 
          <div style="flex:8;">
            <?php if(file_exists($row['image'])):?>
              <!--SLIKA UNUTAR OBJAVE-->
              <div>
                <img src="<?php echo $row['image']?>" style="width:100%; height:150px; object-fit:cover;">
              </div>
            <?php endif;?>
              <!--TEKST OBJAVE-->
              <div>
                <div style="color:#656464; font-size:10px;"><?php echo $row['date']?></div>
                <div style=""><?php echo $row['post']?></div>
                <br><br>
                  <a href="profile.php?action=post_edit&id=<?=$row['id']?>">
                    <button>Izmjeni objavu</button>
                  </a>
                  <a href="profile.php?action=post_delete&id=<?=$row['id']?>">
                    <button>Obriši objavu</button>
                  </a>
                <br><br>
              </div>
            </div>
            </div>
          <?php endwhile;?>
        <?php endif;?>
      </div>
  <?php endif;?>
</div>
<?php include 'footer.php';?>
</body>
</html>