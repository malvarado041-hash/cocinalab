# CocinaLab (Laravel 11)

Migración del proyecto (XAMPP) a Laravel 11 con Auth nativo.
Ya no se requiere XAMPP: se usa MariaDB/MySQL del sistema + `php artisan serve`.

Rutas: `/login /register /home /recetas /filtrar /procedimiento /usuario /info`.

## Requisitos

- PHP ^8.2 + extensiones: `mysql xml curl zip bcmath mbstring`
- Composer
- MariaDB / MySQL

```bash
sudo apt install -y php8.2-mysql php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-mbstring mariadb-server composer
sudo systemctl enable --now mariadb
```

## Instalación desde cero (al descargar/clonar)

```bash
composer install
cp .env.example .env
php artisan key:generate
```

`.env.example` ya viene apuntando a MySQL (`DB_DATABASE=login`, user `root`, clave vacía).
Si tu MySQL tiene clave, ajústala en `.env` → `DB_PASSWORD=`.

## Base de datos

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS login CHARACTER SET utf8mb4;"
php artisan migrate --seed
```

El seeder (`database/seeders/CocinaLabSeeder.php`) carga desde
`database/seeders/data/cocinalab_data.sql`: ingredientes, recetas,
relaciones receta-ingrediente y usuarios (con rol, estado y
`codigo_empleado`; hashes bcrypt compatibles con `Hash::check`).
El admin por defecto es `Administrador` / `admin123`.

Para ver la BD en DbGate: conexión MySQL → `127.0.0.1:3306`,
user `root`, password vacía, base `login`.

## Compartir la base de datos con el equipo

La **estructura** viaja en git como migraciones; los **datos** se
comparten con un volcado SQL versionado en
`database/seeders/data/cocinalab_data.sql`.

### Si tienes datos nuevos (o cambiaste la BD)

```bash
php artisan db:export        # mysqldump → database/seeders/data/cocinalab_data.sql
git add database/seeders/data/cocinalab_data.sql
git commit -m "actualiza datos de la BD"
git push
```

Opciones del comando:

- `--path=ruta/otro.sql` → exporta a otra ruta.
- `--data-only` → solo `INSERT`s (sin `DROP`/`CREATE TABLE`).

El export excluye tablas volátiles (`sessions`, `cache`, `jobs`,
`failed_jobs`) y usa la configuración de `.env` (la contraseña nunca
aparece en el comando del proceso).

### Si eres el compañero que recibe los cambios

```bash
git pull
php artisan migrate           # estructura nueva (p.ej. codigo_empleado)
php artisan db:seed           # refresca los datos del volcado

# o si tu BD está desactualizada / vacía:
php artisan migrate:fresh --seed
```

Qué hace el seed al correr:

- **Restaura completas**: `ingredientes`, `recetas`,
  `receta_ingrediente`, `pago` (se limpian y se re-importan).
- **Importa `users` sin borrar**: no pierdes cuentas locales; las que
  ya existen se omiten (idempotente, sin duplicados).
- Crea/actualiza el admin por defecto si no existe.
- Es seguro correr `db:seed` más de una vez.

> **Nota:** `migrate:fresh` **borra** todas las tablas locales. Usa
> `db:seed` si solo quieres actualizar los datos sin destruir nada.

## Ejecución (sin XAMPP)

```bash
php artisan serve
# abre http://127.0.0.1:8000/login
```

## Notas

- `vendor/`, `.env` y la BD en sí no van en git: se reconstruyen con los pasos de arriba. Lo que sí va en git son las migraciones y el volcado `database/seeders/data/cocinalab_data.sql` (se actualiza con `php artisan db:export`).
- Login con campo `Usuario` (columna `users.name`).

## cambios de enfoque de la app

anteriormente la app estaba pensada para ser un proyecto con un enfoque de "login" y "registro" de usuarios que no necesariamente tenia una funcion clara ya que la app estaba pensada para ser un proyecto de catalogo de recetas de cocina en base a tipos categorias [desayuno, comida, cena, postres] y en base a los ingredientes que el usuario tenga en su casa, la app le recomendaria recetas que pueda hacer con esos ingredientes.

### nuevo enfoque

ahora la app tiene un enfoque mas claro,ya que ahora no esta pensada para usuarios comunes si no mas bien para la gestion de un restaurante permitiendo tener diferentes usuarios con diferentes roles y permisos, permitiendo a los administradores del restaurante gestionar las recetas, ingredientes y procedimientos de manera mas eficiente y organizada asi como la parte de la administración de mesas,meseros etc,para los cocineros el sistema servira de ayuda mediante guias de recetas echas por el administrador para que el cocinero con o sin experiencia pueda seguir las instrucciones sin problemas ni confusiones.

### cambios principales por hacer
- [ ] crear un sistema de roles y permisos para los usuarios del restaurante (administrador, mesero, cocinero)
- [ ] crear un sistema de gestion de vistas en base al rol del usuario (por ejemplo, los meseros no podran ver la parte de administracion de recetas)
- [ ] crear un sistema de gestion de mesas y reservas para el restaurante
- [ ] crear un sistema de gestion de pedidos para los meseros y cocineros
- [ ] crear un sistema de gestion de inventario para los ingredientes y productos del restaurante
- [ ] crear un sistema de reportes y estadisticas para el administrador del restaurante(
    - ventas, ingresos, recetas mas populares, etc.
    - estadisticas de los meseros
    - inventario de ingredientes y productos
    - ganancias y perdidas del restaurante
)

