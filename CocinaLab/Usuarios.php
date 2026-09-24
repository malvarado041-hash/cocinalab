<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="boton.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <title>Usuario</title>
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

    </center>

</body>
</html>
<?php
$conexion = new mysqli("localhost", "root", "", "login");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = $_GET['usuario'];




$resultado = $conexion->query("SELECT correo FROM registros WHERE usuario = '$usuario'");

$correo = $resultado->fetch_assoc();

$conexion->close();

?>
<center>
<h1 style="color: #7d420f;"> Bienvenido</h1>
</center>
<h2 style="color: #7d420f;"> Informacion Del Usuario</h2>
<table border="1">
  
  <tr>
    <td style="font-size: 200%;"><b>Usuario</td>
    <td style="font-size: 150%;"><?php echo $usuario; ?></td>
  </tr>
  <tr>
    <td style="font-size: 200%;"><b>Correo</td>
    <td style="font-size: 150%;"><?php echo $correo['correo']; ?></td>
  </tr>
</table>
