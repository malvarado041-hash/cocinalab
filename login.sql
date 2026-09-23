create database login;
use login;
create table registros (
id int auto_increment,
Usuario varchar(200),
Correo varchar (200),
Contrasena varchar (200),
primary key(id)
);
create table ingredientes (
id int auto_increment,
Nombre varchar(200),
Tipo varchar (200),
primary key(id)
);
create table recetas (
ingredientes varchar(200),
Imagenes varchar(200),
Nombre varchar (200),
Procedimiento text,
primary key(ingredientes)
);
create table pago (
id int auto_increment,
Metodo varchar (200),
Fecha timestamp (200),
Tarjeta varchar (200),
Vencimiento varchar (200),
primary key(id)
);