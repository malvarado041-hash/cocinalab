<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="boton.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <title>Informacion</title>
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
    <center>
   <ul class="btnlist">
      <?php
    echo "<li style='width: 50%;''><a class='boton' href='cocinalab1.php?usuario=$usuario'>Inicio</a></li>";
    echo"<li><a class='boton' href='recetas.php?usuario=$usuario'>Recetas</a></li>";
    echo "<li><a class='boton' href='Usuarios.php?usuario=$usuario'>Usuario</a></li>";
    echo"<li><a class='boton' href='Info.php?usuario=$usuario'>Informacion</a></li>";
    ?>
  </ul>
   


  <h1>Desarrolladores</h1>
  <b><p style="font-size: 20px;">Miguel Alvarado</p>
  <p style="font-size: 20px;">Mitch Flores</p>
  <p style="font-size: 20px;">Gerardo Ruelas</p></b>

  <h2 style="margin-left: 1000px;"> Contactanos</h2>
  <p style="font-size: 20px; margin-left: 1000px;">cocinalab@gmail.com</p>
  </center>

</body>
</html>