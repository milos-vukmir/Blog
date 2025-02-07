<?php 
  require 'connection.php'; 

  //PROVJERA DA LI JE KORISNIK ULOGOVAN
  function check_login()
  {
    if(empty($_SESSION['logged']))
    {
      header("Location: login.php");
      die;
    }
  }
?>