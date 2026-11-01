# Adaptación móvil de CocinaLab

## Auditoría de código

Alcance: todas las vistas Blade de acceso, consulta y administración, incluyendo detalles, edición, bajas, reportes e impresión. Anchos de referencia: 360, 390, 768 y 1280 px, más orientación horizontal.

| Área | Hallazgo | Prioridad | Solución prevista |
| --- | --- | --- | --- |
| Navegación administrativa | Etiqueta style duplicada; drawer con capas por debajo del header; foco no gestionado | Alta | Corregir CSS, capas, cierre y foco del menú |
| Layout administrativo | Altura h-screen y scroll interno sensibles al teclado y barras del navegador | Alta | Viewport dinámico y contenedores flexibles |
| Comandas | Alta de platillos en 12 columnas incluso en 360 px | Alta | Filas apiladas con etiquetas accesibles |
| Mesas y caja | Campos en línea sin ancho adaptable | Alta | Bloques verticales, acciones táctiles y resumen visible |
| Administración | Formularios, modales y tablas con patrones distintos | Media | Reglas compartidas, scroll local y campos fluidos |
| Recetas y acceso | Fuente base de 14 px en móvil; overlay de autenticación fijo | Media | Campos de 16 px, altura dinámica e imágenes fluidas |
| Validación | Pruebas PHP sin cobertura de interacción responsive | Alta | Verificación funcional y checklist por navegador y rol |

Estos hallazgos proceden de inspección de código; no representan una validación visual en dispositivos reales.

## Criterios de aceptación

- Sin desplazamiento horizontal de la página desde 360 px; las tablas anchas pueden desplazarse dentro de su contenedor.
- Objetivos táctiles de 44 px, foco visible y nombres accesibles en controles operativos.
- Formularios utilizables con el teclado abierto, sin acciones ocultas permanentemente.
- Menús cerrados fuera del orden de tabulación; Escape cierra y restaura el foco.
- Modos claro y oscuro, permisos y presentación de escritorio preservados.
- Flujo: acceso → mesa → cuenta → platillos → cocina → servido → cobro → ticket.

## Matriz de revisión manual

En Chrome/Android y Safari/iPhone, revisar a 360 y 390 px; en tablet a 768 px y escritorio a 1280 px:

- Acceso, registro y recuperación con teclado abierto y errores de validación.
- Menú: abrir, Tab/Shift+Tab, Escape, overlay, navegación y cambio de ancho.
- Mesero/capitán: abrir mesa, agregar/quitar platillos, notas largas, unir y dividir.
- Cocinero: avanzar preparación y listo con nombres largos.
- Cajero: abrir/cerrar caja, efectivo, tarjeta, mixto y ticket impreso.
- Almacén: filtros, altas/edición, movimientos, proveedores y bajas/restauración.
- Admin/sistemas: usuarios, recetas con imágenes/ingredientes/pasos, perfil, configuración, auditoría y reportes.
- Estados vacío, error y éxito; modo oscuro; zoom 200%; orientación horizontal.
