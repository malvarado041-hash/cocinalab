<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Procedimiento de la Receta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #B9B9B9;
        }

        .boton {
            background-color: #a66328;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .boton:hover {
            background-color: #8b4f20;
        }

        .procedimiento {
            background-color: #fff;
            padding: 30px;
            width: 60%;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #333;
        }
    </style>
</head>
<body>
    


<?php
$conexion = new mysqli("localhost", "root", "", "login");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);

}


$usuario = $_GET['usuario'] ?? '';
$receta = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $receta_id = (int)$_GET['id'];
    $stmt = $conexion->prepare("SELECT Nombre, Procedimiento FROM recetas WHERE id = ?");
    $stmt->bind_param("i", $receta_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $receta = $resultado->fetch_assoc();
    }
    $stmt->close();

} elseif (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {

    $buscar = "%" . $conexion->real_escape_string(trim($_GET['buscar'])) . "%";
    $stmt = $conexion->prepare("SELECT Nombre, Procedimiento FROM recetas WHERE Nombre LIKE ? LIMIT 1");
    $stmt->bind_param("s", $buscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $receta = $resultado->fetch_assoc();
    }
    $stmt->close();

} else {
    echo "<p style='text-align:center;'>ID de receta no proporcionado.</p>";
    $conexion->close();
    exit;
}

$conexion->close();

if ($receta) {
    echo "<div class='procedimiento'>";
    echo "<h2>" . htmlspecialchars($receta['Nombre']) . "</h2>";
    echo "<p>" . nl2br(htmlspecialchars($receta['Procedimiento'])) . "</p>";
    echo "<a class='boton' href='recetas.php?usuario=" . urlencode($usuario) . "'>Volver a Recetas</a>";
    echo "</div>";
} else {
    echo "<p style='text-align:center;'>No se encontró la receta.</p>";
}
?>


</body>
</html>
