<!DOCTYPE html>
<html>


<head>
  

  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="boton.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   



  <title>Cocinalab</title>
</head>
<body style="background-color:#B9B9B9;">
<?php
$usuario = $_GET['usuario']
?>

<div class="contenedor">
  <img width="10%" src="img/logo.png" alt="Logo">
  <form action="procedimiento.php" method="GET" style="display: inline;">
    <input type="search" class="buscador" id="gsearch" name="buscar" >
    <input type="submit" value="Buscar" style="width: 5%;">
  </form>
  <br>
  <center></center>
</div>


<center>
 <ul class="btnlist">
  <?php
    echo "<li style='width: 50%;''><a class='boton' href='cocinalab1.php?usuario=$usuario'>Inicio</a></li>";
    echo"<li><a class='boton' href='recetas.php?usuario=$usuario'>Recetas</a></li>";
    echo "<li><a class='boton' href='Usuarios.php?usuario=$usuario'>Usuario</a></li>";
    echo"<li><a class='boton' href='Info.php?usuario=$usuario'>Informacion</a></li>";
    ?>
</ul>

  </center>

<div class="container">
  <h2>Para ti</h2>  
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <div class="carousel-inner">
      <div class="item active">
        <center><img src="img\01.jpg" alt="Chiles Rellenos" style="width:50%;"></center>
      </div>

      <div class="item">
        <center><img src="img\02.jpg" alt="Chicago" style="width:50%;"></center>
      </div>
    
      <div class="item">
        <center><img src="img\03.jpg" alt="New york" style="width:50%;"></center>
      </div>
    </div>

    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>



  </div>
</body>
</html>
