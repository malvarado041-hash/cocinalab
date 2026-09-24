<?php
$host = "localhost";
$user = "root"; 
$pass = "";      
$dbname = "login"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (mysqli_connect_error()) {
    exit('Fallo en la conexión de MySQL: ' . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['Usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    $stmt = $conn->prepare("SELECT contrasena FROM registros WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($contrasena, $hashed_password)) {
            $_SESSION['usuario'] = $usuario;
            
             header("Location: http://localhost/trabajos/CocinaLab/cocinalab1.php?usuario=$usuario");
        } else {
            header("Location: http://localhost/trabajos/CocinaLab/1.php");
        }
    } else {
       header("Location: http://localhost/trabajos/CocinaLab/2.php");
    }

    $stmt->close();
}
$conn->close();
?>


