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
`database/seeders/data/cocinalab_data.sql`: 98 ingredientes, 40 recetas,
194 relaciones receta-ingrediente y 3 usuarios (`registros` → `users`,
hashes bcrypt compatibles con `Hash::check`).

Para ver la BD en DbGate: conexión MySQL → `127.0.0.1:3306`,
user `root`, password vacía, base `login`.

## Ejecución (sin XAMPP)

```bash
php artisan serve
# abre http://127.0.0.1:8000/login
```

## Notas

- `vendor/`, `.env` y la BD no van en git: se reconstruyen con los pasos de arriba.
- Login con campo `Usuario` (columna `users.name`).
