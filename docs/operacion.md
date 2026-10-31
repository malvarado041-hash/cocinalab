# Operación de CocinaLab

## Puesta en marcha

Ejecutar `php artisan migrate` y `php artisan serve`. Las migraciones añaden estructura sin borrar registros existentes. La base de pruebas es `login_testing`; nunca ejecutar las pruebas usando la base operativa.

## Preparación de recetas

En Recetas → Vincular ingredientes con almacén se relacionan los ingredientes antiguos. Después, editar cada receta: definir cantidades numéricas, unidades compatibles y las porciones que rinde. No se infieren unidades de datos antiguos: una cantidad sin unidad no es apta para descontar inventario. El precio de venta corresponde a una porción. El costo y margen quedan incompletos hasta completar los ingredientes.

## Servicio e inventario

Crear mesas desde Mesas; abrir cuentas desde una mesa libre. Para llevar se usa una etiqueta sin mesa registrada. Mesero opera sus cuentas, capitán puede asignarlas, cocinero usa Cocina y cajero usa Caja.

Unir y dividir se permite antes de enviar a cocina. Al enviar, los pedidos aparecen en Cocina. Tomar inicia la preparación; Listo descuenta ingredientes una sola vez, dentro de la misma transacción. Si falta stock, unidades o vínculos, el pedido no avanza. Las salidas usan lotes por próxima caducidad; se rechazan lotes vencidos. El stock anterior sin lote sigue disponible sin trazabilidad por lote: registrar los nuevos ingresos con código y caducidad.

Cancelar una cuenta que contiene platos listos clasifica sus consumos como merma y no devuelve existencias. Marcar servida permite cobrarla. La mesa queda ocupada hasta cobrar todas sus cuentas. La división conserva la mesa para que esta permanezca ocupada mientras haya cuentas pendientes.

En Almacén → Movimientos se registran compras, ajustes y mermas. La compra conserva proveedor y precio anterior. Cambiar la unidad de un producto con historial no está permitido. La fecha de la ficha de producto es un dato anterior; las caducidades de cada compra se consultan en Lotes.

## Caja

Cada cajero abre una caja con fondo inicial. El cobro exige una cuenta servida y el importe exacto, distribuido en efectivo y/o tarjeta. Tarjeta significa registro de un pago realizado en terminal externa; CocinaLab no procesa tarjetas ni almacena sus números. El efectivo recibido calcula cambio. Al cerrar, se compara el contado con fondo + cobros en efectivo. Los pagos confirmados no se editan ni se cobran dos veces.

## Reportes y configuración

Reportes → Ventas y operación filtra fechas locales según la zona del restaurante; la base conserva UTC. El margen por platillo usa el costo capturado al preparar; no representa utilidad neta. Las cuentas anteriores sin consumo registrado muestran costo incompleto. Configuración guarda datos del ticket, horarios y moneda. La moneda se bloquea después del primer cobro para evitar mezclar denominaciones.

## Respaldos y recuperación

Requiere `mysqldump` y `mysql` del cliente de MariaDB/MySQL. Ejecutar `php artisan db:backup`; se generan SQL y SHA-256 en `storage/app/private/backups`, con permisos privados. Copiar ambos a un almacenamiento seguro fuera del servidor. No subirlos a Git ni a `public/`: contienen cuentas y hashes de acceso. El respaldo cubre la base; copiar también `storage/app/public` para conservar las imágenes y resguardar `.env` por separado.

Para recuperar:

1. Probar primero en una base vacía de recuperación, con las variables de conexión apuntando a ella. No usar la base operativa para esta prueba.
2. Poner la aplicación operativa en mantenimiento con `php artisan down` y respaldar su estado previo.
3. Configurar la conexión al destino y ejecutar `php artisan db:restore /ruta/respaldo.sql --confirm-database=nombre_base`. Se exige el checksum original. La confirmación identifica el destino que será reemplazado.
4. Restaurar las imágenes si corresponde, ejecutar `php artisan migrate`, `php artisan optimize:clear` y comprobar acceso, recetas, inventario y cobros.
5. Ejecutar `php artisan up`.

Para automatizar el respaldo en el servidor, configurar el planificador del sistema con la frecuencia y retención apropiadas. No se activa un cron automáticamente.
