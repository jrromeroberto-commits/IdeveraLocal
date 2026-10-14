# F2 — Diseño y componentes de IDEVERA STUDIO

Entrega local inicial del 11 de octubre de 2026, reconstruida el 12 de octubre con la dirección B aprobada por el usuario. La portada y el detalle de proyecto de ejemplo comparten identidad, componentes y reglas de movimiento. La F2 entrega una base visual navegable; las operaciones comerciales y de colaboración se implementan en F3–F8. Las decisiones y la evidencia actual están en [revisión visual F2/F3](REVISION_VISUAL_F2_F3.md).

## Cómo revisarla

Desde `idevera-base/`, ejecutar `composer dev` y abrir:

- [Portada](http://127.0.0.1:8000/).
- [Proyecto de ejemplo](http://127.0.0.1:8000/design/project).

La segunda ruta solo funciona con `APP_ENV=local` o `testing`; devuelve 404 en producción. No consulta proyectos reales. El selector «Explorar estados de interfaz» permite revisar carga, vacío, error, acceso restringido, conflicto de versión y conexión perdida. Los comentarios viven en memoria de la pantalla: cerrar y abrir el diálogo los conserva, pero recargar o salir los pierde. No hay envío, aprobación, pago ni persistencia de negocio.

## Dirección implementada

- Paleta oscura y acentos violeta/lila. Pontano Sans variable para titulares públicos e Instrument Sans 400/500/600 para cuerpo y controles. La portada y el prototipo son oscuros; las áreas existentes mantienen la preferencia de apariencia de la cuenta.
- Slogan original «From vision to reality.», índice editorial B, titular amplio y ORBIT dominante junto a FORMA. Son imágenes independientes aprobadas, con derivados WebP sin pérdida y procedencia conservada; siguen identificadas como exploraciones conceptuales.
- Portada con trabajo, los nueve servicios del Word, proceso, estudio, contacto y navegación secundaria. «Iniciar proyecto» lleva a la entrada pública de F3; el brief persistente se conecta en F4.
- Panel de ejemplo con estados operacional y financiero separados, próxima acción, alcance, etapas y siete pestañas. Vista de entregable, formulario de comentarios con validación y confirmación explícita de simulación.
- Marca provisional, favicon y navegación del panel adaptados a IDEVERA; se retiraron los enlaces a la documentación y al repositorio de la plantilla.
- Entrada breve del slogan desde un estado visible, respuesta al pulsar y movimientos de flechas limitados a hover compatible. Se retiró la escultura continua y su botón de pausa al adoptar B. `prefers-reduced-motion: reduce` elimina el movimiento y el contenido permanece visible.

La investigación inicial de Metic, Mirage, TWKS/Onda, Vengeance y Skiper está en [referencias visuales](../REFERENCIAS_VISUALES.md). La reconstrucción actual aplica Impeccable, Taste y Emil a la dirección B elegida: esa propuesta es una guía editable, por instrucción explícita del usuario, y no una reproducción exacta por píxeles.

## Organización

| Archivo | Responsabilidad |
| --- | --- |
| `resources/css/app.css` | Tokens compartidos, identidad del tema oscuro y controles existentes |
| `resources/css/studio.css` | Composición pública, prototipo, responsive, estados y movimiento |
| `resources/css/editorial.css` | Portada B, tipografía pública y entrada del slogan |
| `resources/css/public-pages.css` | Catálogo, lectura, contacto y responsive público |
| `resources/css/workspace.css` | Jerarquía operativa OWNER y CLIENT |
| `resources/js/components/studio/` | Marca, recursos originales, revelado y estados de datos |
| `resources/js/pages/welcome.tsx` | Portada |
| `resources/js/pages/design/project.tsx` | Prototipo del proyecto de CLIENT |
| `app/Http/Controllers/StudioController.php` | Portada y restricción del prototipo al entorno de desarrollo |
| `tests/Feature/DesignPreviewTest.php` | Protección en producción y ausencia de datos reales de proyecto |

Los estilos públicos están delimitados por `.studio-site`; los tokens y componentes existentes sirven de base para las pantallas futuras. Las opciones de formulario, upload, cotización y tablero se amplían cuando existan sus consumidores. Este prototipo no implementa autorización de proyectos ni acredita requisitos de negocio de F4–F8.

## HTML inicial y SSR

La entrada `resources/js/app.tsx` se usa para navegador y SSR mediante el plugin de Inertia 3. `pnpm run build:ssr` genera ambos paquetes. En desarrollo, Vite renderiza mediante `/__inertia_ssr`; una vez calentado su grafo de módulos se comprobó que la respuesta de Laravel contiene `<h1>` y `<main id="contenido">` antes de ejecutar JavaScript del navegador.

La aplicación usa `lang="es"`. La portada incluye descripción y título de IDEVERA; las rutas privadas/prototipo reciben `noindex,nofollow`. Canonical, sitemap, previews sociales y páginas legales se completan en F3/F9 con el contenido correspondiente. En la entrega inicial se inició el servidor del paquete compilado con `php artisan inertia:start-ssr` y se comprobó `/render` con el payload público de aquella home. La reconstrucción B conserva la entrada SSR y sus compilaciones pasan. Para SSR del paquete compilado se necesita Node, además del servidor de Laravel. Esta necesidad debe considerarse al evaluar alojamiento posteriormente.

## Evidencia de la entrega inicial

- `composer ci:check`: 64 pruebas, 330 aserciones; formato PHP, PHPStan, comprobaciones de frontend y TypeScript aprobadas.
- Compilación de navegador y SSR aprobada. Sin dependencias nuevas ni cambios en las reglas de F1.
- La plantilla de restablecimiento de contraseña admite correo `null` y presenta un campo vacío, para evitar la advertencia de React durante SSR; las reglas de recuperación de contraseña se conservan.
- Navegador conectado: home sin desbordamiento horizontal de página a 320, 360, 390, 768, 1024 y 1440 px; proyecto a 360, 390, 768, 1024 y 1440 px. Las pestañas mantienen desplazamiento horizontal dentro de su propio contenedor en móvil.
- Menú móvil: apertura, navegación a servicios y cierre. Servicio expandible y detalle de concepto funcionales.
- Diálogos: cierre con Escape/botón y devolución del foco al activador. Pestañas: flecha derecha cambia selección y foco; Home/End y flecha izquierda están implementados.
- Comentario vacío: aviso asociado al campo, `aria-invalid` y foco en el textarea. Comentario válido: confirmación de ejemplo; borrador conservado al cerrar y reabrir.
- Selector de estados y recuperación tras error revisados. Pausa manual comprobada mediante `animation-play-state: paused`; se comprobó que no oculta contenido. Regla de reducción del sistema revisada en CSS; no se cambió la configuración de animaciones de Windows para simularla.
- Contraste calculado de las combinaciones principales: texto/fondo 18,25:1; secundario/superficie 9,20:1; acento/fondo 7,52:1; blanco/botón primario 5,15:1; título tenue/fondo de servicios 5,46:1. No equivale a una auditoría completa de cada componente de la plataforma.
- No se observaron errores ni advertencias del navegador en las pantallas revisadas. Falta medir Lighthouse, FPS y dispositivos físicos en F9; el reflujo a 320 px no sustituye una prueba de zoom del navegador.

Capturas del navegador local:

- [Home en escritorio](evidencias/f2/home-desktop.jpg).
- [Home en móvil](evidencias/f2/home-mobile.jpg).
- [Proyecto en escritorio](evidencias/f2/project-desktop.jpg).
- [Proyecto en móvil](evidencias/f2/project-mobile.jpg).

Estas capturas y comprobaciones corresponden a la entrega inicial, cuya escultura y pausa manual se sustituyeron al adoptar B. Para el resultado actual, consultar [la revisión de F2/F3](REVISION_VISUAL_F2_F3.md) y sus capturas a 1440, 870 y 390 px. El 13 de octubre se añadió [movimiento de scroll y puntero](MOVIMIENTO_WEB_PUBLICA.md) a petición del usuario, conservando B. F3 administra los textos y medios; F4–F8 conectarán las operaciones del prototipo.
