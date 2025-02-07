<style>
    *{
      padding: 0px;
      margin: 0px;
      box-sizing: border-box;
    }

    a{
      text-decoration: none;
    }

    body{
      background-color: #DDD0C8;
      font-family: cursive;
    }

    header div{
      padding: 16px;
    }

    header a{
      color: #DDD0C8;
    }

    header div:hover{
      background-color: #DDD0C8;
      transition: 0.4s;
    }

    header div:hover a{
      color: #323232;
    }

    header{
      background-color: #323232;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    footer{
      padding: 16px;
      text-align: center;
      background-color: #323232;
      color:#DDD0C8;
    }
    
    /*POCETNA STRANICA*/
    .posts-history{
      margin: auto; 
      max-width: 600px;
    }

    .posts-display{
      display: flex;
      border: solid thin;
      margin: 10px 0px 10px 0px;
    }

    .posts-user-data{
      flex: 1; 
      text-align:center;
      padding-bottom: 10px;
    }

    .posts-content{
      flex: 8;
    }

    /*LOGIN STRANICA*/
    .login{
      margin:auto; 
      max-width:600px;
    }

    /*SIGN UP STRANICA*/
    .sign-up{
      margin:auto;
      max-width:600px;
    }

    /*PROFIL STRANICA */
    .profile-posts{
      margin:auto; 
      max-width:600px;
    }

    input{
      margin: 2px;
      padding: 10px;
      width: 100%;
    }

    h2{
      text-align: center;
      padding-top: 10px;
    }

    textarea{
      margin: 8px;
      padding: 8px;
      width: 100%;
    }

    button{
      padding: 12px;
      margin-top: 5px;
      cursor: pointer;
      background-color: #323232;
      color: #DDD0C8;
      border: none;
      border-radius: 15px;
      min-height:30px; 
      min-width: 120px;
      font-weight: bold;
    }

    button:hover{
      font-size: 14px;
      transition: smooth 0.3s;
      color: #fff;
    }

    hr {
      border: none;
      height: 1px;
      background-color: #323232;
    }
  </style>

  <!-- PRIKAZ NAVIGACIONIH ELEMENATA-->
  <header>
    <div><a href="index.php">Početna stranica</a></div>
    <div><a href="profile.php">Profil</a></div>

    <?php if(empty($_SESSION['logged'])):?> <!--PRIKAZ AKO KORISNIK NIJE ULOGOVAN-->
      <div><a href="login.php">Prijava</a></div>
      <div><a href="signup.php">Registracija</a></div>
    <?php else:?> <!--PRIKAZ AKO JE ULOGOVAN-->
      <div><a href="logout.php">Odjavi se</a></div>
    <?php endif;?>
  </header>  