<?php 
  require "functions.php";
  check_login(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Početna stranica</title>
</head>
<body>
<?php include 'header.php';?>

<div class="posts-history">
  <h2>Istorija objava</h2>

    <?php //DOHVATANJE POSLJEDNJIH 10 OBJAVA IZ BAZE
        $query = "select * from posts order by id desc limit 10";
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
      <div class="posts-display">
<!-- PRIKAZ PROFILNE SLIKE I USERNAME KORISNIKA KOJI JE POSTAVIO OBJAVU (LIJEVA STRANA)-->
          <div class="posts-user-data">
              <img src="<?php echo $user_row['image']?>" style="margin:5px; width:100px; height:100px; object-fit:cover; border-radius:50px;"><br>               
              <?php echo $user_row['username']?>
          </div>
<!-- PRIKAZ SADRZAJA OBJAVE I SLIKE UKOLIKO POSTOJI--> 
          <div class="posts-content">
            <?php if(file_exists($row['image'])):?>
              <!--SLIKA UNUTAR OBJAVE-->
              <div>
                <img src="<?php echo $row['image']?>" style="width:100%; height:150px; object-fit:cover;">
              </div>
            <?php endif;?>
              <!--TEKST OBJAVE-->
              <div>
                <div style="color:grey; font-size:10px;"><?php echo $row['date']?></div>
                <div><?php echo $row['post']?></div>
              </div>
            </div>
            </div>
          <?php endwhile;?>
        <?php endif;?>
      </div>
<?php include 'footer.php';?>
</body>
</html>

