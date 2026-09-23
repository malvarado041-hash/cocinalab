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
        .menu-centrado {
            margin-top: 60px;
        }

        .espaciado {
            margin: 0 15px;
            display: inline-block;
        }

        .dropbtn {
            background-color: #a66328;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #a66328;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #7d420f;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #7d420f;
        }
    </style>
</head>

<body style="background-color:#B9B9B9;">
    <?php
    $conexion = new mysqli("localhost", "root", "", "login");
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    session_start();
    $usuario = $_SESSION['usuario'] ?? 'Invitado';

    function obtenerIngredientesPorTipo($conexion, $tipo)
    {
        $ingredientes = [];
      
        $excluir = ['agua', 'aceite', 'aceite de oliva','sal', 'pimienta', 'azucar', 'canela', 'cebolla blanca', 'cebolla morada', 'cilantro', 'diente ajo', 'diente de ajo', 'ajo en polvo', 'chile serrano', 'chiles serranos', 'pan de caja', 'pan integral', 'tomate rojo', 'tomate verde', 'salsa al gusto', 'aderezo cesar', 'oregano', 'soja', 'arvejas', 'ciabatta', 'jugo de limon', 'vinagre balsamico', 'mayonesa'];

        $placeholders = implode(',', array_fill(0, count($excluir), '?'));

        $sql = "
            SELECT DISTINCT i.Nombre 
            FROM ingredientes i
            JOIN receta_ingrediente ri ON i.id = ri.ingrediente_id
            JOIN recetas r ON r.id = ri.receta_id
            WHERE r.TipoC = ? AND i.Nombre NOT IN ($placeholders)
            ORDER BY i.Nombre
        ";

        $stmt = $conexion->prepare($sql);

        $params = array_merge([$tipo], $excluir);
        $types = str_repeat('s', count($params));

        $bind_names[] = $types;
        foreach ($params as $key => $value) {
            $bind_name = 'bind' . $key;
            $$bind_name = $value;
            $bind_names[] = &$$bind_name;
        }

        call_user_func_array([$stmt, 'bind_param'], $bind_names);

        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($row = $resultado->fetch_assoc()) {
            $ingredientes[] = $row['Nombre'];
        }

        $stmt->close();
        return $ingredientes;
    }

    $tipos = ['Desayuno', 'Comida', 'Cena', 'Postre'];
    $ingredientesPorTipo = [];

    foreach ($tipos as $tipo) {
        $ingredientes = obtenerIngredientesPorTipo($conexion, $tipo);
        if (!empty($ingredientes)) {
            $ingredientesPorTipo[$tipo] = $ingredientes;
        }
    }

    $usuario = $_GET['usuario'];
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
                echo "<li style='width: 50%;'><a class='boton' href='cocinalab1.php?usuario=$usuario'>Inicio</a></li>";
                echo "<li><a class='boton' href='recetas.php?usuario=$usuario'>Recetas</a></li>";
                echo "<li><a class='boton' href='Usuarios.php?usuario=$usuario'>Usuario</a></li>";
                echo "<li><a class='boton' href='Info.php?usuario=$usuario'>Informacion</a></li>";
                ?>
            </ul>

            <center class="menu-centrado">
                <?php foreach ($ingredientesPorTipo as $tipo => $ingredientes) : ?>
                    <div class="dropdown espaciado">
                        <button class="dropbtn"><?= htmlspecialchars($tipo) ?></button>
                        <div class="dropdown-content">
                            <?php foreach ($ingredientes as $ingrediente) : ?>
                                <a href="filtrar.php?tipo=<?= urlencode($tipo) ?>&ingrediente=<?= urlencode($ingrediente) ?>&usuario=<?= urlencode($usuario) ?>">
                                    <?= htmlspecialchars($ingrediente) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </center>
</body>

</html>
