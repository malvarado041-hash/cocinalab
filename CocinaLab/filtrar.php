<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="boton.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>Recetas</title>

    <style>
        .recipe-button {
            display: inline-block;
            margin: 10px;
        }
    </style>
</head>

<body style="background-color:#B9B9B9;">

<?php
$conexion = new mysqli("localhost", "root", "", "login");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$tipo = $_GET['tipo'] ?? '';
$ingrediente = $_GET['ingrediente'] ?? '';
$usuario = $_GET['usuario'] ?? '';

if ($tipo === '' || $ingrediente === '') {
    echo "Faltan parámetros.";
    exit;
}

$stmt = $conexion->prepare("
    SELECT DISTINCT r.id, r.Nombre
    FROM recetas r
    JOIN receta_ingrediente ri ON r.id = ri.receta_id
    JOIN ingredientes i ON ri.ingrediente_id = i.id
    WHERE r.TipoC = ? AND i.Nombre = ?
    ORDER BY r.Nombre
");

$stmt->bind_param("ss", $tipo, $ingrediente);
$stmt->execute();

$resultado = $stmt->get_result();
?>

<div class="contenedor">
  <img width="10%" src="img/logo.png" alt="Logo">
  <form action="procedimiento.php" method="GET" style="display: inline;">
    <input type="search" class="buscador" id="gsearch" name="buscar" >
    <input type="submit" value="Buscar" style="width: 5%;">
  </form>
  <br>
    <center>
        <ul class="btnlist" style="padding-left:0; list-style:none;">
            <?php
            echo "<li style='width: 50%; display: inline-block;'><a class='boton' href='cocinalab1.php?usuario=$usuario'>Inicio</a></li>";
            echo "<li style='display: inline-block; margin-left:10px;'><a class='boton' href='recetas.php?usuario=$usuario'>Recetas</a></li>";
            echo "<li style='display: inline-block; margin-left:10px;'><a class='boton' href='Usuarios.php?usuario=$usuario'>Usuario</a></li>";
            echo "<li style='display: inline-block; margin-left:10px;'><a class='boton' href='Info.php?usuario=$usuario'>Informacion</a></li>";
            ?>
        </ul>
    </center>
</div>
    
<div style="text-align: center;">
    <h2>Recetas de tipo '<?= htmlspecialchars($tipo) ?>' que contienen '<?= htmlspecialchars($ingrediente) ?>':</h2>

    <?php
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo '<form class="recipe-button" method="GET" action="procedimiento.php">';
            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '<input type="hidden" name="usuario" value="' . htmlspecialchars($usuario) . '">';
            echo '<button type="submit" style="padding:10px 20px; background-color:#a66328; color:white; border:none; border-radius:5px; cursor:pointer;">' . htmlspecialchars($row['Nombre']) . '</button>';
            echo '</form>';
        }
    } else {
        echo "<p>No se encontraron recetas con ese ingrediente en esta categoría.</p>";
    }

    $stmt->close();
    $conexion->close();
    ?>
</div>

</body>

</html>
