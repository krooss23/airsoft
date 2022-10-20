<?php

session_start();
  if(!isset($_SESSION['usuario']) ){
    header("location:../index.php");
  }else{

    if($_SESSION["usuario"]=="ok"){
      $nombreUsuario=$_SESSION["nombreUsuario"];
    }
  }

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Administrador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

  <?php $url="http://".$_SERVER['HTTP_HOST']."/web" ?>


    <nav class="navbar navbar-expand navbar-dark bg-info">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="#">Administrador de la web <span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/inicio.php">inicio</a>

            <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/clientes.php">Clientes</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/cerrar.php">cerrar session</a>

            <a class="nav-item nav-link" href="<?php echo $url; ?>">ver web</a>
        </div>
    </nav>

    <div class="container">
      <br>
        <div class="row">