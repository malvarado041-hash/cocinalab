<?php
$conexion = new mysqli("localhost", "root", "", "login");

$hashed_password = password_hash($_GET['contrasena'], PASSWORD_DEFAULT);


$conexion->query("INSERT INTO registros " .
   "(usuario, correo, Contrasena) " . 
   "VALUES " . 
   "('$_GET[txtNombre]', '$_GET[txtCorreo]', '".$hashed_password."') ");

$conexion->close();
header("Location: http://localhost/trabajos/CocinaLab/CocinaLab.html");
?>